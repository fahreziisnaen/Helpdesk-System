const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason,
    fetchLatestBaileysVersion,
    makeCacheableSignalKeyStore,
    isJidGroup
} = require('@whiskeysockets/baileys');
const { Boom } = require('@hapi/boom');
const express = require('express');
const qrcode = require('qrcode');
const fs = require('fs');
const path = require('path');
const pino = require('pino');

const app = express();
app.use(express.json());

const AUTH_PATH = 'whatsapp_info';
const PORT = process.env.PORT || 3000;

let socket = null;
let qrCode = null;
let connectionStatus = 'disconnected';

const logger = pino({ level: 'info' });

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState(AUTH_PATH);
    const { version } = await fetchLatestBaileysVersion();

    socket = makeWASocket({
        version,
        auth: {
            creds: state.creds,
            keys: makeCacheableSignalKeyStore(state.keys, logger),
        },
        printQRInTerminal: true,
        logger
    });

    socket.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            qrCode = await qrcode.toDataURL(qr);
        }

        if (connection === 'close') {
            const shouldReconnect = (lastDisconnect.error instanceof Boom) ?
                lastDisconnect.error.output.statusCode !== DisconnectReason.loggedOut : true;

            console.log('connection closed due to ', lastDisconnect.error, ', reconnecting ', shouldReconnect);
            connectionStatus = 'disconnected';
            qrCode = null;
            if (shouldReconnect) {
                connectToWhatsApp();
            }
        } else if (connection === 'open') {
            console.log('opened connection');
            connectionStatus = 'connected';
            qrCode = null;
        }
    });

    socket.ev.on('creds.update', saveCreds);
}

// API Endpoints
app.get('/status', (req, res) => {
    res.json({
        status: connectionStatus,
        qr: qrCode
    });
});

app.get('/groups', async (req, res) => {
    if (connectionStatus !== 'connected') {
        return res.status(400).json({ error: 'WhatsApp not connected' });
    }
    try {
        const groups = await socket.groupFetchAllParticipating();
        const list = Object.values(groups).map(g => ({
            id: g.id,
            subject: g.subject
        }));
        res.json(list);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

app.post('/send', async (req, res) => {
    const { jid, message } = req.body;
    if (connectionStatus !== 'connected') {
        return res.status(400).json({ error: 'WhatsApp not connected' });
    }
    try {
        await socket.sendMessage(jid, { text: message });
        res.json({ success: true });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

app.post('/reset', (req, res) => {
    console.log('Reset request received. Clearing session and restarting...');
    if (fs.existsSync(AUTH_PATH)) {
        try {
            const files = fs.readdirSync(AUTH_PATH);
            for (const file of files) {
                fs.rmSync(path.join(AUTH_PATH, file), { recursive: true, force: true });
            }
            console.log('Session contents cleared successfully.');
        } catch (err) {
            console.error('Error clearing session contents:', err);
        }
    }
    connectionStatus = 'disconnected';
    qrCode = null;

    // Close socket if exists
    if (socket) {
        try {
            socket.end();
            socket.logout();
        } catch (err) { }
    }

    res.json({ success: true, message: 'Gateway resetting...' });

    // Exit process after a short delay so the response is sent
    // Docker with restart:always will bring it back up fresh
    setTimeout(() => {
        process.exit(1);
    }, 1000);
});

app.listen(PORT, () => {
    console.log(`WhatsApp Gateway listening on port ${PORT}`);
    connectToWhatsApp();
});
