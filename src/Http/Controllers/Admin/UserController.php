<?php

namespace A17\Twill\Http\Controllers\Admin;

use A17\Twill\Models\Enums\UserRole;
use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use PragmaRX\Google2FAQRCode\Google2FA;

class UserController extends ModuleController
{

    protected $namespace = 'A17\Twill';

    protected $moduleName = 'users';

    protected $indexWith = ['medias'];

    protected $defaultOrders = ['name' => 'asc'];

    protected $defaultFilters = [
        'search' => 'search',
    ];

    protected $filters = [
        'role' => 'role',
    ];

    protected $titleColumnKey = 'name';

    protected $indexColumns = [
        'name' => [
            'title' => 'Name',
            'field' => 'name',
        ],
        'email' => [
            'title' => 'Email',
            'field' => 'email',
            'sort' => true,
        ],
        'role_value' => [
            'title' => 'Role',
            'field' => 'role_value',
            'sort' => true,
            'sortKey' => 'role',
        ],
    ];

    protected $indexOptions = [
        'permalink' => false,
    ];

    protected $fieldsPermissions = [
        'role' => 'edit-user-role',
    ];

    function mapStrings($key){
        switch($key){
            case 'name':
                return __('users.name') == 'users.name' ? 'Name' : __('users.name');
            case 'role':
                return __('users.role') == 'users.role' ? 'Role' : __('users.role');
            case 'image':
                return __('users.image') == 'users.image' ? 'Image' : __('users.image');
            case 'users':
                return __('users.users') == 'users.users' ? 'Users' : __('users.users');
            case 'enabled':
                return __('users.enabled') == 'users.enabled' ? 'Enabled' : __('users.enabled');
            case 'disabled':
                return __('users.disabled') == 'users.disabled' ? 'Disabled' : __('users.disabled');
            case 'active':
                return __('users.active') == 'users.active' ? 'Active' : __('users.active');
            case 'trash':
                return __('users.trash') == 'users.trash' ? 'Trash' : __('users.trash');
        }
    }

    function redoIndexCols(){
        $this->indexColumns['name']['title'] = $this->mapStrings('name');
        $this->indexColumns['role_value']['title'] = $this->mapStrings('role');
        if(config('twill.enabled.users-image')){
            $this->indexColumns['image']['title'] = $this->mapStrings('image');
        }
    }

    public function __construct(Application $app, Request $request)
    {
        parent::__construct($app, $request);
        $this->removeMiddleware('can:edit');
        $this->removeMiddleware('can:delete');
        $this->removeMiddleware('can:publish');
        $this->middleware('can:edit-user-role', ['only' => ['index']]);
        $this->middleware('can:edit-user,user', ['only' => ['store', 'edit', 'update', 'destroy', 'bulkDelete', 'restore', 'bulkRestore']]);
        $this->middleware('can:publish-user', ['only' => ['publish']]);

        if (config('twill.enabled.users-image')) {
            $this->indexColumns = [
                'image' => [
                    'title' => 'Image',
                    'thumb' => true,
                    'variant' => [
                        'role' => 'profile',
                        'crop' => 'default',
                    ],
                ],
            ] + $this->indexColumns;
        }
        $this->redoIndexCols();
    }

    protected function indexData($request)
    {
        $enabled = $this->mapStrings('enabled');
        $disabled = $this->mapStrings('disabled');
        $users = $this->mapStrings('users');
        return [
            'defaultFilterSlug' => 'published',
            'create' => $this->getIndexOption('create') && auth('twill_users')->user()->can('edit-user-role'),
            'roleList' => collect(UserRole::toArray()),
            'single_primary_nav' => [
                'users' => [
                    'title' => $users,
                    'module' => true,
                ],
            ],
            'customPublishedLabel' => $enabled,
            'customDraftLabel' => $disabled,
        ];
    }

    protected function formData($request)
    {
        $users = $this->mapStrings('users');
        $enabled = $this->mapStrings('enabled');
        $disabled = $this->mapStrings('disabled');
        $user = Auth::guard('twill_users')->user();
        $with2faSettings = config('twill.enabled.users-2fa') && $user->id == request('user');

        if ($with2faSettings) {
            $google2fa = new Google2FA();

            if (is_null($user->google_2fa_secret)) {
                $secret = $google2fa->generateSecretKey();
                $user->google_2fa_secret = \Crypt::encrypt($secret);
                $user->save();
            }

            $qrCode = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                \Crypt::decrypt($user->google_2fa_secret),
                200
            );
        }

        return [
            'roleList' => collect(UserRole::toArray()),
            'single_primary_nav' => [
                'users' => [
                    'title' => $users,
                    'module' => true,
                ],
            ],
            'customPublishedLabel' => $enabled,
            'customDraftLabel' => $disabled,
            'with2faSettings' => $with2faSettings,
            'qrCode' => $qrCode ?? null,
        ];
    }

    protected function getRequestFilters()
    {
        return json_decode($this->request->get('filter'), true) ?? ['status' => 'published'];
    }

    public function getIndexTableMainFilters($items, $scopes = [])
    {
        $active = $this->mapStrings('active');
        $disabled = $this->mapStrings('disabled');
        $trash = $this->mapStrings('trash');
        $statusFilters = [];

        array_push($statusFilters, [
            'name' => $active,
            'slug' => 'published',
            'number' => $this->repository->getCountByStatusSlug('published'),
        ], [
            'name' => $disabled,
            'slug' => 'draft',
            'number' => $this->repository->getCountByStatusSlug('draft'),
        ]);

        if ($this->getIndexOption('restore')) {
            array_push($statusFilters, [
                'name' => $trash,
                'slug' => 'trash',
                'number' => $this->repository->getCountByStatusSlug('trash'),
            ]);
        }

        return $statusFilters;
    }

    protected function getIndexOption($option)
    {
        if (in_array($option, ['publish', 'delete', 'restore'])) {
            return auth('twill_users')->user()->can('edit-user-role');
        }

        return parent::getIndexOption($option);
    }

    protected function indexItemData($item)
    {
        $canEdit = auth('twill_users')->user()->can('edit-user-role') || auth('twill_users')->user()->id === $item->id;
        return [
            'edit' => $canEdit ? $this->getModuleRoute($item->id, 'edit') : null,
        ];
    }
}
