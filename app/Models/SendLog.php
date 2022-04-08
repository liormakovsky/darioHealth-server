<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class SendLog extends Model
{
    use HasFactory;

      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'send_log';

    public $timestamps = false;

    protected $fillable = [
         'log_messsage',
         'log_success',
         'log_created'
    ];
}