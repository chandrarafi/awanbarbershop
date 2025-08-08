<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'otp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'otp_code', 'type', 'expired_at'];


    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function generateOTP($userId, $type = 'registration')
    {

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);


        $expiredAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));


        $this->where('user_id', $userId)
            ->where('type', $type)
            ->delete();


        $this->insert([
            'user_id' => $userId,
            'otp_code' => $otp,
            'type' => $type,
            'expired_at' => $expiredAt
        ]);

        return $otp;
    }

    public function verifyOTP($userId, $otp, $type = 'registration')
    {
        $otpData = $this->where([
            'user_id' => $userId,
            'otp_code' => $otp,
            'type' => $type
        ])->first();

        if (!$otpData) {
            return false;
        }


        if (strtotime($otpData['expired_at']) < time()) {
            return false;
        }


        $this->delete($otpData['id']);

        return true;
    }
}
