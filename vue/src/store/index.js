import {createStore} from "vuex";
import axiosClient from "../axios.js";


const tmpPatient = [
  {
    id: 50,
    name: 'Ahmed Farag',
    email: 'ahmed@test.com',
    ssn: '12345678912345',
    phone_number: '01005885834',
    profile_pic: '',
    gender: 'male',
    is_blocked: false,
  },
  {
    id: 60,
    name: 'Mohamed Farag',
    email: 'mohamed@test.com',
    ssn: '12345673212345',
    phone_number: '011205885834',
    profile_pic: '',
    gender: 'male',
    is_blocked: true,
  },
  {
    id: 70,
    name: 'Samira saeed',
    email: 'samir@test.com',
    ssn: '12341238912345',
    phone_number: '01105665834',
    profile_pic: '',
    gender: 'female',
    is_blocked: false,
  },
]

const store = createStore({
    state: {
        user: {
            data:{
              name: 'Tom Cook',
              email: 'tom@example.com',
              imageUrl: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
            },
            token: sessionStorage.getItem("TOKEN"),
        },
      patients: [...tmpPatient],

    },
    getters: {},
    actions: {

      register({ commit }, user){
        return axiosClient.post('/register', user)
          .then(({data}) => {
            commit('setUser', data);
            return data;
          })
      },

      login({ commit }, user){
        return axiosClient.post('/login', user)
          .then(({data}) => {
            commit('setUser', data);
            return data;
          })
      },

      logout({ commit }){
        return axiosClient.get('/logout')
          .then(response => {
            commit('logout');
            return response;
          })
      },
    },
    mutations: {
      logout: (state) => {
        state.user.data = {};
        state.user.token = null;
      },
      setUser: (state, userData) => {
        state.user.token = userData.data.token;
        state.user.data = userData.data.user;
        sessionStorage.setItem('TOKEN', userData.data.token);
      },
    },
    modules: {},

})

export default store;
