import { createRouter, createWebHistory } from 'vue-router'
import { store } from '../store'
import Signin from '../views/auth/Signin.vue'
import Signup from '../views/auth/Signup.vue'

// Lazy
const MyQuizzes = () => import('../views/MyQuizzes.vue')
const QuizLeads = () => import('../views/QuizLeads.vue')
const QuizEdit = () => import('../views/quiz/QuizEdit.vue')
const QuizPublishSettings = () => import('../views/quiz/QuizPublishSettings.vue')
const EmailVerify = () => import('../views/auth/EmailVerify.vue')
const EmailVerifyCode = () => import('../views/auth/EmailVerifyCode.vue')

const routes = [
    {
        path: '/',
        name: 'MyQuizzes',
        component: MyQuizzes,
        meta: { auth: true },
    },
    {
        path: '/:id/edit',
        name: 'QuizEdit',
        component: QuizEdit,
        meta: { auth: true },
        children: [
            {
                path: 'publish-settings',
                name: 'QuizPublishSettings',
                component: QuizPublishSettings
            }
        ]
    },
    {
        path: '/leads',
        name: 'QuizLeads',
        component: QuizLeads,
        meta: { auth: true },
    },
    {
        path: '/signin',
        name: 'Signin',
        component: Signin,
        meta: { auth: false },
    },
    {
        path: '/signup',
        name: 'Signup',
        component: Signup,
        meta: { auth: false },
    },
    {
        path: '/verify',
        name: 'EmailVerify',
        component: EmailVerify,
        meta: { auth: true },
        children: [
            {
                path: ':code(.*)',
                name: 'EmailVerifyCode',
                component: EmailVerifyCode
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(route => route.meta.auth)) {
        if (store.getters['users/isLogged']) {
            return next()
        } else {
            next({ name: 'Signin' })
        }
    } else {
        next()
    }
})

export { router }