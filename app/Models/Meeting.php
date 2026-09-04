<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'link',
        'duration',
        'password',
        'price',
        'thumbnail',
        'status',
        'start_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Parse duration into minutes (e.g. "60 Mins", "1.5 Hours", "90", etc.)
     */
    public function getDurationMinutesAttribute(): int
    {
        if (preg_match('/(\d+(?:\.\d+)?)\s*(?:hour|hr)/i', $this->duration, $m)) {
            return (int)round(((float)$m[1]) * 60);
        }
        if (preg_match('/(\d+)/', $this->duration, $m)) {
            return (int)$m[1];
        }
        return 60; // Default 60 minutes
    }

    /**
     * Compute meeting end time based on start_time + duration
     */
    public function getEndTimeAttribute(): ?Carbon
    {
        if (!$this->start_time) {
            return null;
        }
        return $this->start_time->copy()->addMinutes($this->duration_minutes);
    }

    /**
     * A meeting is LIVE if current time is between start_time and end_time
     */
    public function isLive(): bool
    {
        if (!$this->start_time) {
            return false;
        }
        $now = Carbon::now();
        $endTime = $this->end_time;
        return $now->gte($this->start_time) && ($endTime ? $now->lte($endTime) : true);
    }

    /**
     * A meeting is UPCOMING if current time is before start_time
     */
    public function isUpcoming(): bool
    {
        if (!$this->start_time) {
            return true;
        }
        return Carbon::now()->lt($this->start_time);
    }

    /**
     * A meeting is PAST if current time is after end_time
     */
    public function isPast(): bool
    {
        if (!$this->start_time) {
            return false;
        }
        $endTime = $this->end_time;
        return $endTime ? Carbon::now()->gt($endTime) : false;
    }

    /**
     * Automatically determine status based on Scheduled Start Time and Duration
     */
    public function getStatusAttribute($value): string
    {
        if ($this->isLive()) {
            return 'live';
        }
        if ($this->isUpcoming()) {
            return 'upcoming';
        }
        return 'past';
    }

    /**
     * Human readable time indicator
     */
    public function getTimeIndicatorAttribute(): string
    {
        $now = Carbon::now();
        if (!$this->start_time) {
            return 'Schedule Pending';
        }

        if ($this->isLive()) {
            $end = $this->end_time;
            if ($end) {
                return 'Live Now (Ends ' . $end->diffForHumans() . ')';
            }
            return 'Live Now';
        }

        if ($this->isUpcoming()) {
            return 'Starts ' . $this->start_time->diffForHumans();
        }

        $end = $this->end_time;
        return 'Ended ' . ($end ? $end->diffForHumans() : $this->start_time->diffForHumans());
    }

    public function getFormattedPriceAttribute(): string
    {
        if (!preg_match('/[a-zA-Z$€£]/', $this->price)) {
            return $this->price . ' AED';
        }
        return $this->price;
    }
}
