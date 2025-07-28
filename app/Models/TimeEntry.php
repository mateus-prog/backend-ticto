<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    public const TABLE = 'time_entries';
    protected $table = self::TABLE;
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'entry_date',
        'entry_time',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'entry_date' => 'date',
            'entry_time' => 'datetime:H:i:s',
        ];
    }

    /**
     * Relacionamento: Cada batida de ponto pertence a um utilizador.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}