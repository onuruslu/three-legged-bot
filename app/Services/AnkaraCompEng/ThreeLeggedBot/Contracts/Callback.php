<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Contracts;

interface Callback {
	public function handle(int $id);
}