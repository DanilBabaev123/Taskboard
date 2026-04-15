import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../views/Dashboard.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import TeamShow from '../views/TeamShow.vue';
import BoardShow from '../views/BoardShow.vue';

const routes = [
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/register', component: () => import('../views/Register.vue') },
    { path: '/teams/:id', component: TeamShow, meta: { requiresAuth: true }, name: 'team.show' },
    { path: '/boards/:id', component: BoardShow, meta: { requiresAuth: true }, name: 'board.show' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.meta.requiresAuth && !token) next('/login');
    else next();
});

export default router;
