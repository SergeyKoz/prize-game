<?php

namespace App\Console\Commands;

use App\Mail\SubjectMail;
use App\Models\SubjectPrizes;
use App\Models\User;
use App\Prizes\SubjectPrize;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SendSubjectToEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prize-game:send-subject-to-email
                            {--subject= : Subject title}
                            {--email= : User Email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends subjects to user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subject = $this->option('subject');
        $email = $this->option('email');

        $errors = Validator::make([
            'subject' =>  $subject,
            'email' => $email,
        ], [
            'subject' => 'required|string|in:' . implode(',', SubjectPrize::SUBJECT),
            'email' => 'required|email',
        ])->errors();

        if (!$errors->isEmpty()) {
            foreach ($errors->all() as $error) {
                $this->error($error);
            }

            return 1;
        }

        $user = User::firstWhere('email', $email);

        if (!$user) {
            $this->error('User is not found');

            return 1;
        }

        $subjectCount = SubjectPrizes::query()
            ->where('refused', false)
            ->where('sent', false)
            ->where('user_id', $user->id)
            ->where('title', $subject)
            ->update(['sent' => 1]);

        if ($subjectCount > 0) {
            Mail::to($user)->send(new SubjectMail($subjectCount));
        } else {
            $this->warn('Subjects are not found');
        }

        return 0;
    }
}
