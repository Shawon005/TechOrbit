<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TechOrbitContentStore
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/techorbit-content.json');
    }

    public function all(): array
    {
        $data = $this->load();
        $changed = false;

        foreach ($this->collectionPaths() as $path) {
            $items = data_get($data, $path, []);
            if (! is_array($items)) {
                continue;
            }

            foreach ($items as $index => $item) {
                if (! isset($item['id'])) {
                    $items[$index]['id'] = (string) Str::uuid();
                    $changed = true;
                }
            }

            data_set($data, $path, $items);
        }

        if ($changed) {
            $this->persist($data);
        }

        return $data;
    }

    public function collection(string $path): array
    {
        return data_get($this->all(), $path, []);
    }

    public function find(string $path, string $id): ?array
    {
        foreach ($this->collection($path) as $item) {
            if (($item['id'] ?? null) === $id) {
                return $item;
            }
        }

        return null;
    }

    public function create(string $path, array $payload): array
    {
        $data = $this->all();
        $items = data_get($data, $path, []);
        $payload['id'] = (string) Str::uuid();
        $items[] = $payload;
        data_set($data, $path, array_values($items));
        $this->persist($data);

        return $payload;
    }

    public function prepend(string $path, array $payload): array
    {
        $data = $this->all();
        $items = data_get($data, $path, []);
        $payload['id'] = (string) Str::uuid();
        array_unshift($items, $payload);
        data_set($data, $path, array_values($items));
        $this->persist($data);

        return $payload;
    }

    public function update(string $path, string $id, array $payload): ?array
    {
        $data = $this->all();
        $items = data_get($data, $path, []);
        $updated = null;

        foreach ($items as $index => $item) {
            if (($item['id'] ?? null) !== $id) {
                continue;
            }

            $payload['id'] = $id;
            $items[$index] = $payload;
            $updated = $payload;
            break;
        }

        if (! $updated) {
            return null;
        }

        data_set($data, $path, array_values($items));
        $this->persist($data);

        return $updated;
    }

    public function delete(string $path, string $id): void
    {
        $data = $this->all();
        $items = array_values(array_filter(
            data_get($data, $path, []),
            fn (array $item): bool => ($item['id'] ?? null) !== $id
        ));

        data_set($data, $path, $items);
        $this->persist($data);
    }

    public function updateMany(array $values): void
    {
        $data = $this->all();

        foreach ($values as $path => $value) {
            data_set($data, $path, $value);
        }

        $this->persist($data);
    }

    private function load(): array
    {
        if (! File::exists($this->path)) {
            $data = config('techorbit');
            $this->persist($data);

            return $data;
        }

        $decoded = json_decode(File::get($this->path), true);

        if (! is_array($decoded)) {
            $decoded = config('techorbit');
            $this->persist($decoded);
        }

        return $this->mergeMissing($decoded, config('techorbit'));
    }

    private function persist(array $data): void
    {
        File::ensureDirectoryExists(dirname($this->path));
        File::put(
            $this->path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    private function collectionPaths(): array
    {
        return [
            'heroSlides',
            'software',
            'services',
            'projects',
            'team',
            'posts',
            'testimonials',
            'admin.inquiries',
        ];
    }

    private function mergeMissing(mixed $stored, mixed $defaults): mixed
    {
        if (! is_array($stored) || ! is_array($defaults)) {
            return $stored ?? $defaults;
        }

        foreach ($defaults as $key => $defaultValue) {
            if (! array_key_exists($key, $stored)) {
                $stored[$key] = $defaultValue;
                continue;
            }

            $stored[$key] = $this->mergeMissing($stored[$key], $defaultValue);
        }

        return $stored;
    }
}
