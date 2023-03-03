import {createRouter, createWebHistory} from "vue-router";

import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Patients from "../views/Patients.vue";
import PatientView from "../views/PatientView.vue";
import Doctors from "../views/Doctors.vue";
import DoctorView from "../views/DoctorView.vue";

import DefaultLayout from "../components/DefaultLayout.vue";
import AuthLayout from "../components/AuthLayout.vue";
import store from "../store/index.js";

const routes = [

  {
    path: '/',
    redirect: '/dashboard',
    component: DefaultLayout,
    meta: {requiresAuth: true},
    children: [
      {path: '/dashboard', name: 'Dashboard', component: Dashboard},

      {path: '/patients', name: 'Patients', component: Patients},
      {path: '/patients/create', name: 'PatientCreate', component: PatientView},
      {path: '/patients/:id', name: 'PatientView', component: PatientView},

      {path: '/doctors', name: 'Doctors', component: Doctors},
      {path: '/doctors/create', name: 'DoctorCreate', component: DoctorView},
      {path: '/doctors/:id', name: 'DoctorView', component: DoctorView},


    ],
  },
  {
    path: '/auth',
    redirect: '/login',
    name: 'Auth',
    component: AuthLayout,
    meta: {isGuest: true},
    children: [
      {
        path: '/login',
        name: 'Login',
        component: Login,
      },
      {
        path: '/register',
        name: 'Register',
        component: Register,
      },
    ],
  },

];


const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {

  if (to.meta.requiresAuth && !store.state.user.token) {
    next({name: 'Login'})
  } else if (store.state.user.token && to.meta.isGuest) {
    next({name: 'Dashboard'});
  } else {
    next()
  }

})

export default router;
