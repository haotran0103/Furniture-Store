const auth = [
    {
      path: '/auth',
      name: 'auth',
      component: () => {
        return import('../layouts/auth.vue');
      },
      children: [
        {
          path: 'login',
          name: 'auth-login',
          component: () => {
            return import('../pages/auth/login/index.vue');
          },
          meta: {
            auth: false
          },
        },
        {
          path: 'register',
          name: 'auth-register',
          component: () => {
            return import('../pages/auth/register/index.vue');
          },
        },
        {
          path: 'user/register',
          name: 'auth-user-register',
          component: () => {
            return import('../pages/user/auth/register/index.vue');
          },
        },
        {
          path: 'user/login',
          name: 'auth-user-login',
          component: () => {
            return import('../pages/user/auth/login/index.vue');
          },
        },
      ],
    },
  ];
  export default auth;