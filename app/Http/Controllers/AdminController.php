<?php

namespace App\Http\Controllers;

use App\Support\TechOrbitContentStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly TechOrbitContentStore $store)
    {
    }

    public function dashboard(): View
    {
        $site = $this->store->all();
        $newInquiryCount = collect($site['admin']['inquiries'])
            ->where('status', 'New')
            ->count();
        $stats = [
            ['label' => 'Total Projects', 'value' => (string) count($site['projects']), 'change' => 'Editable from admin', 'tone' => 'teal'],
            ['label' => 'New Inquiries', 'value' => (string) $newInquiryCount, 'change' => 'Saved from the contact form', 'tone' => 'gold'],
            ['label' => 'Blog Posts', 'value' => (string) count($site['posts']), 'change' => 'Ready to publish', 'tone' => 'green'],
            ['label' => 'Team Members', 'value' => (string) count($site['team']), 'change' => 'Shown on About page', 'tone' => 'blue'],
        ];

        return view('admin.dashboard', $this->adminData($site, 'dashboard', [
            'sectionTitle' => 'Dashboard',
            'sectionDescription' => 'Overview of content, inquiries, publishing, and quick management links.',
            'stats' => $stats,
            'inquiries' => $site['admin']['inquiries'],
            'actions' => $site['admin']['actions'],
            'chart' => $site['admin']['chart'],
        ]));
    }

    public function heroSlides(Request $request): View
    {
        return $this->collectionPage($request, 'hero-slides');
    }

    public function services(Request $request): View
    {
        return $this->collectionPage($request, 'services');
    }

    public function portfolio(Request $request): View
    {
        return $this->collectionPage($request, 'portfolio');
    }

    public function team(Request $request): View
    {
        return $this->collectionPage($request, 'team');
    }

    public function blog(Request $request): View
    {
        return $this->collectionPage($request, 'blog');
    }

    public function inquiries(Request $request): View
    {
        return $this->collectionPage($request, 'inquiries');
    }

    public function testimonials(Request $request): View
    {
        return $this->collectionPage($request, 'testimonials');
    }

    public function storeCollection(Request $request, string $section): RedirectResponse
    {
        $definition = $this->sectionDefinition($section);
        $payload = $this->validatedPayload($request, $definition['fields'], $section);
        $item = $this->store->create($definition['path'], $payload);

        return redirect()
            ->route($definition['route'], ['edit' => $item['id']])
            ->with('success', $definition['singular'].' created successfully.');
    }

    public function updateCollection(Request $request, string $section, string $id): RedirectResponse
    {
        $definition = $this->sectionDefinition($section);
        $current = $this->store->find($definition['path'], $id) ?? [];
        $payload = $this->validatedPayload($request, $definition['fields'], $section, $current);
        $this->store->update($definition['path'], $id, $payload);

        return redirect()
            ->route($definition['route'], ['edit' => $id])
            ->with('success', $definition['singular'].' updated successfully.');
    }

    public function destroyCollection(string $section, string $id): RedirectResponse
    {
        $definition = $this->sectionDefinition($section);
        $this->store->delete($definition['path'], $id);

        return redirect()
            ->route($definition['route'])
            ->with('success', $definition['singular'].' deleted successfully.');
    }

    public function settings(): View
    {
        $site = $this->store->all();
        $fields = $this->settingsFields();

        return view('admin.settings', $this->adminData($site, 'settings', [
            'sectionTitle' => 'Settings',
            'sectionDescription' => 'Brand, contact, and platform defaults that shape the public website experience.',
            'settingsFields' => $this->preparedFields($fields, $site),
        ]));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $fields = $this->settingsFields();
        $values = $this->validatedValues($request, $fields);

        $this->store->updateMany($values);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }

    private function collectionPage(Request $request, string $section): View
    {
        $site = $this->store->all();
        $definition = $this->sectionDefinition($section);
        $items = $this->store->collection($definition['path']);
        $selected = $request->filled('edit')
            ? $this->store->find($definition['path'], $request->string('edit')->toString())
            : ($items[0] ?? null);
        $isCreating = $request->boolean('create') || ! $selected;
        $model = $isCreating ? $this->blankModel($definition['fields']) : $selected;

        return view('admin.collection', $this->adminData($site, $section, [
            'sectionTitle' => $definition['title'],
            'sectionDescription' => $definition['description'],
            'tableColumns' => $definition['columns'],
            'tableRows' => $this->tableRows($section, $items, $definition['route']),
            'formTitle' => ($isCreating ? 'Add ' : 'Edit ').$definition['singular'],
            'formFields' => $this->preparedFields($definition['fields'], $model),
            'formAction' => $isCreating
                ? route('admin.content.store', ['section' => $section])
                : route('admin.content.update', ['section' => $section, 'id' => $selected['id']]),
            'formMethod' => $isCreating ? 'POST' : 'PUT',
            'createUrl' => route($definition['route'], ['create' => 1]),
            'selectedId' => $selected['id'] ?? null,
            'previewTitle' => $definition['preview_title'],
            'previewLines' => $this->previewLines($definition['fields'], $model),
        ]));
    }

    private function tableRows(string $section, array $items, string $route): array
    {
        return collect($items)->values()->map(function (array $item, int $index) use ($section, $route): array {
            return [
                'id' => $item['id'],
                'cells' => $this->rowCells($section, $item, $index),
                'tone' => $item['tone'] ?? ($index === 0 ? 'new' : 'read'),
                'edit_url' => route($route, ['edit' => $item['id']]),
                'delete_url' => route('admin.content.destroy', ['section' => $section, 'id' => $item['id']]),
            ];
        })->all();
    }

    private function rowCells(string $section, array $item, int $index): array
    {
        return match ($section) {
            'hero-slides' => [
                (string) ($index + 1),
                data_get($item, 'title.en', ''),
                data_get($item, 'badge.en', ''),
                'Contact Page',
                $index === 0 ? 'Live' : 'Draft',
            ],
            'services' => [
                (string) ($index + 1),
                data_get($item, 'title.en', ''),
                Str::limit(data_get($item, 'excerpt.en', ''), 56),
                $item['slug'] ?? '',
                'Active',
            ],
            'portfolio' => [
                (string) ($index + 1),
                data_get($item, 'title.en', ''),
                $item['client'] ?? '',
                strtoupper($item['category'] ?? ''),
                $item['year'] ?? '',
            ],
            'team' => [
                (string) ($index + 1),
                $item['name'] ?? '',
                data_get($item, 'role.en', ''),
                $item['initials'] ?? '',
                'Visible',
            ],
            'blog' => [
                (string) ($index + 1),
                data_get($item, 'title.en', ''),
                data_get($item, 'category.en', ''),
                $item['date'] ?? '',
                data_get($item, 'read_time.en', ''),
            ],
            'inquiries' => [
                $item['initials'] ?? '',
                $item['name'] ?? '',
                $item['service'] ?? '',
                $item['date'] ?? '',
                $item['status'] ?? '',
            ],
            'testimonials' => [
                (string) ($index + 1),
                $item['name'] ?? '',
                data_get($item, 'role.en', ''),
                '5 stars',
                'Published',
            ],
            default => [],
        };
    }

    private function previewLines(array $fields, array $model): array
    {
        if (isset($model['message'])) {
            return array_filter([
                $model['name'] ?? null,
                $model['email'] ?? null,
                $model['phone'] ?? null,
                $model['message'] ?? null,
            ]) ?: ['No preview data yet.'];
        }

        $lines = collect($fields)
            ->map(fn (array $field): string => trim((string) data_get($model, $field['path'], '')))
            ->filter()
            ->take(4)
            ->values()
            ->all();

        return $lines === [] ? ['No preview data yet.'] : $lines;
    }

    private function preparedFields(array $fields, array $model): array
    {
        return collect($fields)->map(function (array $field) use ($model): array {
            $field['input_name'] = $this->inputName($field['path']);
            $field['value'] = data_get($model, $field['path'], '');

            return $field;
        })->all();
    }

    private function blankModel(array $fields): array
    {
        $model = [];

        foreach ($fields as $field) {
            data_set($model, $field['path'], $field['default'] ?? '');
        }

        return $model;
    }

    private function validatedPayload(Request $request, array $fields, string $section, array $current = []): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $rules[$field['path']] = $field['rules'] ?? ['nullable', 'string', 'max:1000'];
        }

        $validated = $request->validate($rules);
        $payload = [];

        foreach ($fields as $field) {
            if (($field['type'] ?? 'text') === 'file') {
                $uploadedFile = $request->file($field['path']);
                $existing = data_get($current, $field['path'], $field['default'] ?? '');

                if ($uploadedFile && is_string($existing) && str_starts_with($existing, '/uploads/techorbit/')) {
                    $existingFile = public_path(ltrim($existing, '/'));
                    if (File::exists($existingFile)) {
                        File::delete($existingFile);
                    }
                }

                data_set(
                    $payload,
                    $field['path'],
                    $uploadedFile ? $this->storeUploadedFile($uploadedFile, $section) : $existing
                );

                continue;
            }

            data_set($payload, $field['path'], data_get($validated, $field['path'], $field['default'] ?? ''));
        }

        return $payload;
    }

    private function validatedValues(Request $request, array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $rules[$field['path']] = $field['rules'] ?? ['nullable', 'string', 'max:1000'];
        }

        $validated = $request->validate($rules);
        $values = [];

        foreach ($fields as $field) {
            $values[$field['path']] = data_get($validated, $field['path'], $field['default'] ?? '');
        }

        return $values;
    }

    private function inputName(string $path): string
    {
        $parts = explode('.', $path);
        $name = array_shift($parts);

        foreach ($parts as $part) {
            $name .= '['.$part.']';
        }

        return $name;
    }

    private function storeUploadedFile($file, string $section): string
    {
        $directory = public_path('uploads/techorbit/'.$section);
        File::ensureDirectoryExists($directory);

        $extension = $file->getClientOriginalExtension() ?: 'png';
        $filename = Str::uuid().'.'.$extension;
        $file->move($directory, $filename);

        return '/uploads/techorbit/'.$section.'/'.$filename;
    }

    private function adminData(array $site, string $activeKey, array $data = []): array
    {
        $newInquiryCount = collect($site['admin']['inquiries'] ?? [])
            ->where('status', 'New')
            ->count();

        $menu = collect($this->adminMenu($newInquiryCount))->map(function (array $group) use ($activeKey): array {
            $group['items'] = collect($group['items'])->map(function (array $item) use ($activeKey): array {
                $item['active'] = $item['key'] === $activeKey;

                return $item;
            })->all();

            return $group;
        })->all();

        return array_merge([
            'company' => $site['company'],
            'adminMenu' => $menu,
            'pageTitle' => 'TechOrbit Admin Panel',
            'pageDescription' => 'Admin experience for TechOrbit IT website content management.',
            'activeAdminKey' => $activeKey,
            'newInquiryCount' => $newInquiryCount,
        ], $data);
    }

    private function adminMenu(int $inquiryCount): array
    {
        return [
            [
                'section' => 'Main',
                'items' => [
                    ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => 'admin.dashboard', 'badge' => null],
                ],
            ],
            [
                'section' => 'Content',
                'items' => [
                    ['key' => 'hero-slides', 'label' => 'Hero Slides', 'route' => 'admin.hero-slides', 'badge' => null],
                    ['key' => 'services', 'label' => 'Services', 'route' => 'admin.services', 'badge' => null],
                    ['key' => 'portfolio', 'label' => 'Portfolio', 'route' => 'admin.portfolio', 'badge' => null],
                    ['key' => 'team', 'label' => 'Team', 'route' => 'admin.team', 'badge' => null],
                    ['key' => 'blog', 'label' => 'Blog', 'route' => 'admin.blog', 'badge' => null],
                ],
            ],
            [
                'section' => 'Manage',
                'items' => [
                    ['key' => 'inquiries', 'label' => 'Inquiries', 'route' => 'admin.inquiries', 'badge' => (string) $inquiryCount],
                    ['key' => 'testimonials', 'label' => 'Testimonials', 'route' => 'admin.testimonials', 'badge' => null],
                    ['key' => 'settings', 'label' => 'Settings', 'route' => 'admin.settings', 'badge' => null],
                ],
            ],
        ];
    }

    private function settingsFields(): array
    {
        return [
            ['path' => 'company.name', 'label' => 'Company Name', 'rules' => ['required', 'string', 'max:120']],
            ['path' => 'company.email', 'label' => 'Support Email', 'rules' => ['required', 'email', 'max:120']],
            ['path' => 'company.phone', 'label' => 'Phone', 'rules' => ['required', 'string', 'max:60']],
            ['path' => 'company.whatsapp', 'label' => 'WhatsApp', 'rules' => ['required', 'string', 'max:60']],
            ['path' => 'company.location.en', 'label' => 'Location (EN)', 'rules' => ['required', 'string', 'max:120']],
            ['path' => 'company.location.bn', 'label' => 'Location (BN)', 'rules' => ['required', 'string', 'max:120']],
            ['path' => 'company.address.en', 'label' => 'Address (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
            ['path' => 'company.address.bn', 'label' => 'Address (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
            ['path' => 'ui.quote_cta.en', 'label' => 'Quote CTA (EN)', 'rules' => ['required', 'string', 'max:60']],
            ['path' => 'ui.quote_cta.bn', 'label' => 'Quote CTA (BN)', 'rules' => ['required', 'string', 'max:60']],
            ['path' => 'company.socials.0.url', 'label' => 'Facebook URL', 'rules' => ['required', 'url', 'max:255']],
            ['path' => 'company.socials.1.url', 'label' => 'LinkedIn URL', 'rules' => ['required', 'url', 'max:255']],
            ['path' => 'company.socials.2.url', 'label' => 'GitHub URL', 'rules' => ['required', 'url', 'max:255']],
        ];
    }

    private function sectionDefinition(string $section): array
    {
        $definitions = [
            'hero-slides' => [
                'path' => 'heroSlides',
                'route' => 'admin.hero-slides',
                'title' => 'Hero Slides',
                'singular' => 'Hero slide',
                'description' => 'Manage the homepage slider, headlines, supporting copy, and call-to-action flow.',
                'columns' => ['Order', 'Headline', 'Badge', 'CTA', 'Status', 'Actions'],
                'preview_title' => 'Homepage Hero Preview',
                'fields' => [
                    ['path' => 'badge.en', 'label' => 'Badge (EN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'badge.bn', 'label' => 'Badge (BN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'title.en', 'label' => 'Headline (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
                    ['path' => 'title.bn', 'label' => 'Headline (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
                    ['path' => 'eyebrow.en', 'label' => 'Eyebrow (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
                    ['path' => 'eyebrow.bn', 'label' => 'Eyebrow (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
                    ['path' => 'subtitle.en', 'label' => 'Subtitle (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                    ['path' => 'subtitle.bn', 'label' => 'Subtitle (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                ],
            ],
            'services' => [
                'path' => 'services',
                'route' => 'admin.services',
                'title' => 'Services',
                'singular' => 'Service',
                'description' => 'Control homepage and services-page cards, ordering, descriptions, and delivery highlights.',
                'columns' => ['Order', 'Service', 'Summary', 'Slug', 'Status', 'Actions'],
                'preview_title' => 'Service Preview',
                'fields' => [
                    ['path' => 'icon', 'label' => 'Icon Label', 'rules' => ['required', 'string', 'max:10']],
                    ['path' => 'slug', 'label' => 'Slug', 'rules' => ['required', 'alpha_dash', 'max:80']],
                    ['path' => 'image', 'label' => 'Service Image', 'type' => 'file', 'rules' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'], 'default' => '/assets/images/service-card.svg'],
                    ['path' => 'title.en', 'label' => 'Title (EN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'title.bn', 'label' => 'Title (BN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'excerpt.en', 'label' => 'Excerpt (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
                    ['path' => 'excerpt.bn', 'label' => 'Excerpt (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
                    ['path' => 'description.en', 'label' => 'Description (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:800']],
                    ['path' => 'description.bn', 'label' => 'Description (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:800']],
                    ['path' => 'deliverables.0.en', 'label' => 'Deliverable 1 (EN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'deliverables.0.bn', 'label' => 'Deliverable 1 (BN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'deliverables.1.en', 'label' => 'Deliverable 2 (EN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'deliverables.1.bn', 'label' => 'Deliverable 2 (BN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'deliverables.2.en', 'label' => 'Deliverable 3 (EN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'deliverables.2.bn', 'label' => 'Deliverable 3 (BN)', 'rules' => ['required', 'string', 'max:120']],
                ],
            ],
            'portfolio' => [
                'path' => 'projects',
                'route' => 'admin.portfolio',
                'title' => 'Portfolio',
                'singular' => 'Project',
                'description' => 'Showcase featured work, project metadata, technology stacks, and category filters.',
                'columns' => ['Order', 'Project', 'Client', 'Category', 'Year', 'Actions'],
                'preview_title' => 'Project Preview',
                'fields' => [
                    ['path' => 'image', 'label' => 'Project Image', 'type' => 'file', 'rules' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'], 'default' => '/assets/images/project-card.svg'],
                    ['path' => 'title.en', 'label' => 'Project Name (EN)', 'rules' => ['required', 'string', 'max:150']],
                    ['path' => 'title.bn', 'label' => 'Project Name (BN)', 'rules' => ['required', 'string', 'max:150']],
                    ['path' => 'summary.en', 'label' => 'Summary (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                    ['path' => 'summary.bn', 'label' => 'Summary (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                    ['path' => 'client', 'label' => 'Client', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'tech', 'label' => 'Tech Stack', 'rules' => ['required', 'string', 'max:150']],
                    ['path' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => ['web' => 'Web', 'mobile' => 'Mobile', 'erp' => 'ERP', 'design' => 'Design'], 'rules' => ['required', 'string', 'max:40']],
                    ['path' => 'gradient', 'label' => 'Card Gradient', 'type' => 'select', 'options' => ['mint' => 'Mint', 'sky' => 'Sky', 'violet' => 'Violet', 'coral' => 'Coral', 'lime' => 'Lime', 'sun' => 'Sun'], 'rules' => ['required', 'string', 'max:40']],
                    ['path' => 'year', 'label' => 'Year', 'rules' => ['required', 'string', 'max:10']],
                ],
            ],
            'team' => [
                'path' => 'team',
                'route' => 'admin.team',
                'title' => 'Team',
                'singular' => 'Team member',
                'description' => 'Present leadership, delivery, design, and engineering contributors across the About page.',
                'columns' => ['Order', 'Member', 'Role', 'Initials', 'Status', 'Actions'],
                'preview_title' => 'Team Card Preview',
                'fields' => [
                    ['path' => 'name', 'label' => 'Full Name', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'initials', 'label' => 'Initials', 'rules' => ['required', 'string', 'max:8']],
                    ['path' => 'image', 'label' => 'Member Image', 'type' => 'file', 'rules' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'], 'default' => '/assets/images/team-card.svg'],
                    ['path' => 'role.en', 'label' => 'Role (EN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'role.bn', 'label' => 'Role (BN)', 'rules' => ['required', 'string', 'max:120']],
                ],
            ],
            'blog' => [
                'path' => 'posts',
                'route' => 'admin.blog',
                'title' => 'Blog',
                'singular' => 'Post',
                'description' => 'Keep thought leadership articles fresh across the homepage and blog listing page.',
                'columns' => ['Order', 'Article', 'Category', 'Published', 'Read Time', 'Actions'],
                'preview_title' => 'Article Preview',
                'fields' => [
                    ['path' => 'category.en', 'label' => 'Category (EN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'category.bn', 'label' => 'Category (BN)', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'date', 'label' => 'Publish Date', 'rules' => ['required', 'string', 'max:40']],
                    ['path' => 'read_time.en', 'label' => 'Read Time (EN)', 'rules' => ['required', 'string', 'max:40']],
                    ['path' => 'read_time.bn', 'label' => 'Read Time (BN)', 'rules' => ['required', 'string', 'max:40']],
                    ['path' => 'title.en', 'label' => 'Title (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
                    ['path' => 'title.bn', 'label' => 'Title (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:255']],
                    ['path' => 'excerpt.en', 'label' => 'Excerpt (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                    ['path' => 'excerpt.bn', 'label' => 'Excerpt (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                ],
            ],
            'inquiries' => [
                'path' => 'admin.inquiries',
                'route' => 'admin.inquiries',
                'title' => 'Inquiries',
                'singular' => 'Inquiry',
                'description' => 'Track incoming leads, prioritize follow-ups, and monitor response state from one queue.',
                'columns' => ['ID', 'Name', 'Service', 'Received', 'Status', 'Actions'],
                'preview_title' => 'Inquiry Preview',
                'fields' => [
                    ['path' => 'initials', 'label' => 'Initials', 'rules' => ['required', 'string', 'max:8']],
                    ['path' => 'name', 'label' => 'Name', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'email', 'label' => 'Email', 'rules' => ['required', 'email', 'max:120']],
                    ['path' => 'phone', 'label' => 'Phone', 'rules' => ['required', 'string', 'max:40']],
                    ['path' => 'company', 'label' => 'Company', 'rules' => ['nullable', 'string', 'max:120']],
                    ['path' => 'service', 'label' => 'Service', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'budget', 'label' => 'Budget', 'rules' => ['nullable', 'string', 'max:120']],
                    ['path' => 'date', 'label' => 'Received', 'rules' => ['required', 'string', 'max:60']],
                    ['path' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['New' => 'New', 'Read' => 'Read', 'Replied' => 'Replied'], 'rules' => ['required', 'string', 'max:20']],
                    ['path' => 'tone', 'label' => 'Tone', 'type' => 'select', 'options' => ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied'], 'rules' => ['required', 'string', 'max:20']],
                    ['path' => 'message', 'label' => 'Message', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:2000']],
                ],
            ],
            'testimonials' => [
                'path' => 'testimonials',
                'route' => 'admin.testimonials',
                'title' => 'Testimonials',
                'singular' => 'Testimonial',
                'description' => 'Curate trust-building quotes for the homepage slider and future case-study support.',
                'columns' => ['Order', 'Client', 'Role', 'Rating', 'Status', 'Actions'],
                'preview_title' => 'Testimonial Preview',
                'fields' => [
                    ['path' => 'name', 'label' => 'Client Name', 'rules' => ['required', 'string', 'max:120']],
                    ['path' => 'role.en', 'label' => 'Role (EN)', 'rules' => ['required', 'string', 'max:150']],
                    ['path' => 'role.bn', 'label' => 'Role (BN)', 'rules' => ['required', 'string', 'max:150']],
                    ['path' => 'message.en', 'label' => 'Message (EN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                    ['path' => 'message.bn', 'label' => 'Message (BN)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
                ],
            ],
        ];

        abort_unless(isset($definitions[$section]), 404);

        return $definitions[$section];
    }
}
