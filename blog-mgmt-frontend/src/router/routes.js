const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        component: () => import('pages/IndexPage.vue'),
        name: 'Home',
        meta: { requiresAuth: true },
      },
      {
        path: 'users',
        component: () => import('pages/users/UserListPage.vue'),
        name: 'UserList',
        meta: { requiresAuth: true },
      },
      {
        path: 'users/add',
        component: () => import('pages/users/UserAdd.vue'),
        name: 'UserAdd',
        meta: { requiresAuth: true },
      },
      {
        path: 'users/:id',
        component: () => import('pages/users/UserDetail.vue'),
        name: 'UserDetail',
        meta: { requiresAuth: true },
      },
      {
        path: 'blogs',
        component: () => import('pages/blogs/BlogListPage.vue'),
        name: 'BlogList',
        meta: { requiresAuth: true },
      },
      {
        path: 'blogs/add',
        component: () => import('pages/blogs/BlogAdd.vue'),
        name: 'BlogAdd',
        meta: { requiresAuth: true },
      },
      {
        path: 'blogs/:id',
        component: () => import('pages/blogs/BlogDetail.vue'),
        name: 'BlogDetail',
        meta: { requiresAuth: true },
      },
      // Uncomment if you want to add these routes later
      // {
      //   path: 'permissions',
      //   component: () => import('pages/permissions/PermissionsPage.vue'),
      //   name: 'Permissions',
      //   meta: { requiresAuth: true },
      // },
      // {
      //   path: 'roles',
      //   component: () => import('pages/roles/RolesPage.vue'),
      //   name: 'Roles',
      //   meta: { requiresAuth: true },
      // },
      // {
      //   path: 'blogs',
      //   component: () => import('pages/blogs/BlogsPage.vue'),
      //   name: 'Blogs',
      //   meta: { requiresAuth: true },
      // }
    ]
  },
  {
    path: '/login',
    component: () => import('pages/login/LoginIndex.vue'),
    name: 'Login'
  },
  {
    path: '/register',
    component: () => import('pages/register/RegisterIndex.vue'),
    name: 'Register'
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
    name: 'NotFound'
  }
];

export default routes;
