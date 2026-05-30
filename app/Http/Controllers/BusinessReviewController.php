<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessReviewRequest;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\BusinessReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BusinessReviewController extends Controller
{
    public function store(StoreBusinessReviewRequest $request, Business $business): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isClient(), 403, 'Solo los clientes pueden publicar reseñas.');

        $hasCompletedAppointment = Appointment::query()
            ->where('user_id', $user->id)
            ->where('business_id', $business->id)
            ->where('status', Appointment::STATUS_COMPLETED)
            ->exists();

        abort_unless($hasCompletedAppointment, 403, 'Debes completar una cita para reseñar este negocio.');

        $review = BusinessReview::query()->firstOrNew([
            'business_id' => $business->id,
            'user_id' => $user->id,
        ]);

        if ($review->exists) {
            Storage::disk('public')->delete($review->imagePaths());
        }

        $images = collect($request->file('images', []))
            ->filter(static fn ($file): bool => $file instanceof UploadedFile && $file->isValid())
            ->map(static fn (UploadedFile $file): string => $file->store('reviews/'.$business->id, 'public'))
            ->values()
            ->all();

        $review->fill([
            'rating' => (int) $request->integer('rating'),
            'title' => $request->string('title')->toString() ?: null,
            'comment' => $request->string('comment')->toString(),
            'images' => $images,
        ])->save();

        return redirect()
            ->route('public.businesses.show', $business->slug)
            ->with('status', 'Reseña guardada correctamente. Gracias por compartir tu experiencia.');
    }
}

