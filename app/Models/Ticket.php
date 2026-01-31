<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'title',
        'description',
        'category',
        'category_id',
        'priority',
        'status',
        'attachment',
        'solved_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'solved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created the ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the teknisi assigned to this ticket
     */
    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all messages for this ticket
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get all activities for this ticket
     */
    public function activities()
    {
        return $this->hasMany(TicketActivity::class);
    }

    /**
     * Get notifications for this ticket
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the category for this ticket
     */
    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Generate unique ticket number
     */
    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('ticket_number', $number)->exists());

        return $number;
    }
}
