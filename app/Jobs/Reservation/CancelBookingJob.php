<?php

namespace App\Jobs\Reservation;

use App\Interfaces\ReservationInterface;
use App\Interfaces\ReservationStatusInterface;
use App\Interfaces\TableModelInterface;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelBookingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $reservation;

    /**
     * Create a new job instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     */
    public function handle(
        ReservationInterface $reservationRepo,
        TableModelInterface $tableModelRepo,
        ReservationStatusInterface $reservationStatusRepo
    ): void
    {
        $status = $reservationStatusRepo->getByName('Cancel');
        $reservationRepo->update($this->reservation->id, ['status_id' => $status->id]);
        $tableModelRepo->update($this->reservation->table_id, ['is_available' => true]);
    }
}
