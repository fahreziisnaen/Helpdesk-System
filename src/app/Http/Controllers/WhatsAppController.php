<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function index()
    {
        $status = $this->whatsapp->getStatus();
        $groups = [];
        
        if (isset($status['status']) && $status['status'] === 'connected') {
            $groups = $this->whatsapp->getGroups();
        }

        $selectedGroupId = Setting::get('whatsapp_group_id');

        return view('admin.whatsapp.index', compact('status', 'groups', 'selectedGroupId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required',
        ]);

        Setting::set('whatsapp_group_id', $request->group_id);

        return back()->with('success', 'Group ID berhasil disimpan.');
    }

    public function reset()
    {
        if ($this->whatsapp->resetConnection()) {
            return back()->with('success', 'Koneksi WhatsApp berhasil direset. Silakan scan ulang QR Code.');
        }

        return back()->with('error', 'Gagal mereset koneksi WhatsApp.');
    }
}
