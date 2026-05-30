<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Business;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request, Business $business): View
    {
        $this->ensureBusinessOwnership($request, $business);

        return view('services.index', [
            'business' => $business,
            'services' => $business->services()->latest()->get(),
        ]);
    }

    public function create(Request $request, Business $business): View
    {
        $this->ensureBusinessOwnership($request, $business);

        return view('services.create', [
            'business' => $business,
        ]);
    }

    public function store(StoreServiceRequest $request, Business $business): RedirectResponse
    {
        $this->ensureBusinessOwnership($request, $business);

        $payload = $request->safe()->except(['images', 'remove_images']);

        $service = $business->services()->create($payload);

        $galleryImages = $this->storeUploadedImages($request, $business);

        if ($galleryImages !== []) {
            $service->update([
                'gallery_images' => $galleryImages,
            ]);
        }

        return redirect()
            ->route('businesses.services.index', $business)
            ->with('status', 'Servicio creado correctamente.');
    }

    public function edit(Request $request, Business $business, Service $service): View
    {
        $this->ensureSameBusiness($business, $service);
        $this->ensureBusinessOwnership($request, $business);

        return view('services.edit', [
            'business' => $business,
            'service' => $service,
        ]);
    }

    public function update(UpdateServiceRequest $request, Business $business, Service $service): RedirectResponse
    {
        $this->ensureSameBusiness($business, $service);
        $this->ensureBusinessOwnership($request, $business);

        $payload = $request->safe()->except(['images', 'remove_images']);
        $existingImages = $service->galleryImagePaths();
        $imagesToRemove = collect($request->input('remove_images', []))
            ->filter(static fn ($path): bool => is_string($path) && $path !== '')
            ->values()
            ->all();

        if ($imagesToRemove !== []) {
            Storage::disk('public')->delete($imagesToRemove);
            $existingImages = array_values(array_diff($existingImages, $imagesToRemove));
        }

        $newImages = $this->storeUploadedImages($request, $business);

        $service->update([
            ...$payload,
            'gallery_images' => array_values([...$existingImages, ...$newImages]),
        ]);

        return redirect()
            ->route('businesses.services.edit', [$business, $service])
            ->with('status', 'Servicio actualizado correctamente.');
    }

    private function ensureBusinessOwnership(Request $request, Business $business): void
    {
        abort_unless(
            $request->user()?->isAdmin() || $business->user_id === $request->user()?->id,
            403,
            'No puedes gestionar servicios de un negocio que no te pertenece.'
        );
    }

    private function ensureSameBusiness(Business $business, Service $service): void
    {
        abort_unless(
            $service->business_id === $business->id,
            404
        );
    }

    private function storeUploadedImages(Request $request, Business $business): array
    {
        $files = $request->file('images', []);

        if (! is_array($files) || $files === []) {
            return [];
        }

        $directory = 'services/'.$business->id;

        return collect($files)
            ->filter(static fn ($file): bool => $file instanceof UploadedFile && $file->isValid())
            ->map(static fn (UploadedFile $file): string => $file->store($directory, 'public'))
            ->values()
            ->all();
    }
}
