import {createRouter, createWebHistory} from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Main from "@/layouts/Main.vue";
import Auth from "@/layouts/Auth.vue";
import Login from "@/views/Login.vue";
import {useAuthStore} from "@/stores/auth.js";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            component: Main,
            children: [
                {
                    path: '',
                    name: 'dashboard',
                    component: Dashboard,
                    meta: {requiresAuth: true, permission: 'dashboard-menu'}
                },
                // head-of-family
                {
                    path: 'head-of-family',
                    name: 'headOfFamily',
                    component: () => import('@/views/head-of-family/Index.vue'),
                    meta: {requiresAuth: true, permission: 'head-of-family-list'},
                },
                {
                    path: 'head-of-family/add',
                    name: 'headOfFamilyAdd',
                    component: () => import('@/views/head-of-family/Add.vue'),
                    meta: {requiresAuth: true, permission: 'head-of-family-create'},
                },
                {
                    path: 'head-of-family/manage/:id',
                    name: 'headOfFamilyManage',
                    component: () => import('@/views/head-of-family/Manage.vue'),
                    meta: {requiresAuth: true, permission: 'head-of-family-list'},
                },

                // social-assistance
                {
                    path: 'social-assistance',
                    name: 'socialAssistance',
                    component: () => import('@/views/social-assistance/Index.vue'),
                    meta: {requiresAuth: true, permission: 'social-assistance-list'},
                },
                {
                    path: 'social-assistance/add',
                    name: 'socialAssistanceAdd',
                    component: () => import('@/views/social-assistance/Add.vue'),
                    meta: {requiresAuth: true, permission: 'social-assistance-create'},
                },
                {
                    path: 'social-assistance/edit/:id',
                    name: 'socialAssistanceEdit',
                    component: () => import('@/views/social-assistance/Edit.vue'),
                    meta: {requiresAuth: true, permission: 'social-assistance-edit'},
                },
                {
                    path: 'social-assistance/manage/:id',
                    name: 'socialAssistanceManage',
                    component: () => import('@/views/social-assistance/Manage.vue'),
                    meta: {requiresAuth: true, permission: 'social-assistance-list'},
                },

                // event
                {
                    path: 'event',
                    name: 'event',
                    component: () => import('@/views/event/Index.vue'),
                    meta: {requiresAuth: true, permission: 'event-list'},
                },
                {
                    path: 'event/add',
                    name: 'eventAdd',
                    component: () => import('@/views/event/Add.vue'),
                    meta: {requiresAuth: true, permission: 'event-create'},
                },
                {
                    path: 'event/edit/:id',
                    name: 'eventEdit',
                    component: () => import('@/views/event/Edit.vue'),
                    meta: {requiresAuth: true, permission: 'event-edit'},
                },
                {
                    path: 'event/manage/:id',
                    name: 'eventManage',
                    component: () => import('@/views/event/Manage.vue'),
                    meta: {requiresAuth: true, permission: 'event-list'},
                },

                // development
                {
                    path: 'development',
                    name: 'development',
                    component: () => import('@/views/development/Index.vue'),
                    meta: {requiresAuth: true, permission: 'development-list'},
                },
                {
                    path: 'development/add',
                    name: 'developmentAdd',
                    component: () => import('@/views/development/Add.vue'),
                    meta: {requiresAuth: true, permission: 'development-create'},
                },
                {
                    path: 'development/edit/:id',
                    name: 'developmentEdit',
                    component: () => import('@/views/development/Edit.vue'),
                    meta: {requiresAuth: true, permission: 'development-edit'},
                },
                {
                    path: 'development/manage/:id',
                    name: 'developmentManage',
                    component: () => import('@/views/development/Manage.vue'),
                    meta: {requiresAuth: true, permission: 'development-list'},
                },

                // social-assistance-recipient
                {
                    path: 'social-assistance-recipient',
                    name: 'socialAssistanceRecipient',
                    component: () => import('@/views/social-assistance-recipient/Index.vue'),
                    meta: {requiresAuth: true, permission: 'social-assistance-recipient-list'},
                },
                {
                    path: 'social-assistance-recipient/manage/:id',
                    name: 'socialAssistanceRecipientManage',
                    component: () => import('@/views/social-assistance-recipient/Manage.vue'),
                    meta: {requiresAuth: true, permission: 'social-assistance-recipient-list'},
                },

                // profile-village
                {
                    path: 'profile-village',
                    name: 'profileVillage',
                    component: () => import('@/views/profile-village/Index.vue'),
                    meta: {requiresAuth: true, permission: 'profile-menu'},
                },
                {
                    path: 'profile-village/add',
                    name: 'profileVillageAdd',
                    component: () => import('@/views/profile-village/Add.vue'),
                    meta: {requiresAuth: true, permission: 'profile-create'},
                },
                {
                    path: 'profile-village/edit/:id',
                    name: 'profileVillageEdit',
                    component: () => import('@/views/profile-village/Edit.vue'),
                    meta: {requiresAuth: true, permission: 'profile-edit'},
                },

                // family-member
                {
                    path: 'family-member',
                    name: 'familyMember',
                    component: () => import('@/views/family-member/Index.vue'),
                    meta: {requiresAuth: true, permission: 'family-member-list'},
                },
                {
                    path: 'family-member/add',
                    name: 'familyMemberAdd',
                    component: () => import('@/views/family-member/Add.vue'),
                    meta: {requiresAuth: true, permission: 'family-member-create'},
                },
                {
                    path: 'family-member/manage/:id',
                    name: 'familyMemberManage',
                    component: () => import('@/views/family-member/Manage.vue'),
                    meta: {requiresAuth: true, permission: 'family-member-list'},
                },
            ]
        },
        {
            path: '/login',
            component: Auth,
            children: [
                {
                    path: '',
                    name: 'login',
                    component: Login,
                    meta: {requiresUnAuth: true}
                }
            ]
        },
        // Error pages
        {
            path: '/403',
            name: 'error403',
            component: () => import('@/views/errors/403.vue'),
            meta: {requiresAuth: false}
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'error404',
            component: () => import('@/views/errors/404.vue'),
            meta: {requiresAuth: false}
        }
    ],
})

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.requiresAuth) {
        if (authStore.token) {
            try {
                // Fetch user data jika belum ada
                if (!authStore.user) {
                    await authStore.checkAuth()
                }

                const userPermission = authStore.user?.permissions || []

                // Check permission
                if (to.meta.permission && !userPermission.includes(to.meta.permission)) {
                    next({name: 'error403'})
                    return
                }

                next()
            } catch (error) {
                // Jika gagal fetch user (token invalid/expired), redirect ke login
                next({name: 'login'})
            }
        } else {
            next({name: 'login'})
        }
    } else if (to.meta.requiresUnAuth && authStore.token) {
        next({name: 'dashboard'})
    } else {
        next()
    }
})

export default router