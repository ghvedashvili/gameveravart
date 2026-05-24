<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('questions')->insert([

            // ── Level 1: Nickname game ──────────────────────────────────────────
            // NicknameController handles the logic; LevelController still fetches
            // this row, but the view doesn't render question/answer fields.
            [
                'level'      => 1,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode([]),
                'hints'      => null,
                'type'       => 'action',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 2: Google CAPTCHA ─────────────────────────────────────────
            // CaptchaController fetches this row but doesn't use question/answer.
            [
                'level'      => 2,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode([]),
                'hints'      => null,
                'type'       => 'action',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 3: Russia is Occupier URL puzzle ──────────────────────────
            // RussiaIsOccupierController fetches this row. User must change the
            // URL "RUSSIAISNOTOCCUPIER" → "RUSSIAISOCCUPIER" to pass.
            [
                'level'      => 3,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode([]),
                'hints'      => null,
                'type'       => 'action',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 4: ERROR 004 – hidden URL puzzle ──────────────────────────
            // User must navigate to /levels/4/complete to advance.
            // LevelController fetches this row for the initial page render.
            [
                'level'      => 4,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode([]),
                'hints'      => null,
                'type'       => 'action',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 5: Coca-Cola can – 3D rotation ───────────────────────────
            // Question: "დაასახელე მწარმოებელი კომპანიის patron-ი"
            // TODO: შეავსე სწორი პასუხი/პასუხები
            [
                'level'      => 5,
                'question'   => 'დაასახელე მწარმოებელი კომპანიის patron-ი',
                'rules'      => null,
                'answer'     => json_encode(['TODO_ANSWER']),
                'hints'      => null,
                'type'       => 'question',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 6: Folder / paper – print hint ───────────────────────────
            // Hints inside paper: "ძველი მეთოდები ბევრად საიმედოა" (print the page).
            // TODO: შეავსე სწორი პასუხი
            [
                'level'      => 6,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode(['TODO_ANSWER']),
                'hints'      => null,
                'type'       => 'question',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 7: Domino – Braille puzzle ───────────────────────────────
            // Braille letters on dominos: F-I-R-E (top) + M-A-N (bottom) = FIREMAN
            [
                'level'      => 7,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode(['fireman']),
                'hints'      => null,
                'type'       => 'question',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 8: iPhone mockup – Ocean's Eleven ─────────────────────────
            // Notifications are from Ocean's Eleven characters planning to rob Bellagio.
            // TODO: შეავსე სწორი პასუხი (e.g. "danny ocean", "bellagio", etc.)
            [
                'level'      => 8,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode(['TODO_ANSWER']),
                'hints'      => null,
                'type'       => 'question',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 9: Dino – internet puzzle ────────────────────────────────
            // User disconnects internet → offline dino appears → reconnects → Continue btn.
            [
                'level'      => 9,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode([]),
                'hints'      => null,
                'type'       => 'action',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 10: Formula 1 slot machine ───────────────────────────────
            // Slot machine shows F1 driver codes (VER, HAM, LEC, etc.).
            // TODO: შეავსე სწორი პასუხი
            [
                'level'      => 10,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode(['TODO_ANSWER']),
                'hints'      => null,
                'type'       => 'question',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── Level 11: Generic / final level ────────────────────────────────
            // TODO: თუ level 11 კითხვის ტიპისაა, შეავსე question და answer.
            [
                'level'      => 11,
                'question'   => null,
                'rules'      => null,
                'answer'     => json_encode([]),
                'hints'      => null,
                'type'       => 'action',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
