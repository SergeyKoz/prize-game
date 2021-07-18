<?php

namespace App\Prizes;

use App\Interfaces\Prizes\PrizeInterface;
use App\Models\SubjectPrizes;
use App\Models\User;
use App\Services\LimitsService;
use Exception;

class SubjectPrize implements PrizeInterface
{
    const SUBJECT = [
        'Car',
        'Plane',
        'Train',
        'Scooter',
    ];

    private string $subject;

    private User $user;

    private LimitsService $limitsService;

    public function __construct(string $subject, User $user, LimitsService $limitsService)
    {
        $this->subject = $subject;
        $this->user = $user;
        $this->limitsService = $limitsService;
    }

    public static function createPrize(User $user)
    {
        $subject = self::SUBJECT[random_int(0, count(self::SUBJECT) - 1)];

        $limitsService = new LimitsService($user);

        $amount = $limitsService->getAllowedAmount('subject', 1);

        if ($amount === 0) {
            return null;
        }

        return new self($subject, $user, $limitsService);
    }

    /**
     * @throws Exception
     */
    public function applyPrize() : SubjectPrizes
    {
        $amount = $this->limitsService->getAllowedAmount('subject', 1);

        if ($amount > 0) {
            $subjectPrize = SubjectPrizes::create([
                'user_id' => $this->user->id,
                'title' => $this->subject,
            ]);

            $this->limitsService->updateCurrentLimit('subject', $amount);

            return $subjectPrize;
        } else {
            throw new Exception('Limit exceeded');
        }

    }
}
