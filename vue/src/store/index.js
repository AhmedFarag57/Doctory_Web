import {createStore} from "vuex";
import axiosClient from "../axios.js";

const tmpDoctors = [
  {
    id: 66,
    name: 'Youssef Hany',
    email: 'bsygroves0@domainmarket.com',
    ssn: '32869207861448',
    profile_pic: '',
    phone_number: '+351 479 804 3535',
    clinic_address: '40 Anniversary Alley',
    certifications: [
      {
        id: 5,
        name: 'certification 1'
      },
      {
        id: 10,
        name: 'certification 2'
      }
    ],
    session_price: 490.43,
    rating: 1.50
  },
  {
    id: 67,
    name: 'Ahmed Mohamed',
    email: 'rmadison1@jugem.jp',
    ssn: '25133616462649',
    profile_pic: '',
    phone_number: '+48 879 189 5185',
    clinic_address: '2630 Cambridge Park',
    certifications: [
      {
        id: 1,
        name: 'certification 3'
      },
      {
        id: 2,
        name: 'certification 4'
      }
    ],
    session_price: 450.97,
    rating: 2.5
  },
  {
    id: 69,
    name: 'Abdelrahman Walid',
    email: 'sarndell3@time.com',
    ssn: '30582594955749',
    profile_pic: '',
    phone_number: '+62 171 282 3960',
    clinic_address: '4419 Cottonwood Avenue',
    certifications: [],
    session_price: 238.73,
    rating: 4.00
  },
  {
    id: 70,
    name: 'Hend Hany',
    email: 'klohan4@about.me',
    ssn: '22753211259672',
    profile_pic: '',
    phone_number: '+7 899 225 7449',
    clinic_address: '3819 7th Parkway',
    certifications: [],
    session_price: 440.84,
    rating: 3.00
  },
  {
    id: 71,
    name: 'Brietta Sygroves',
    email: 'bsygroves0@domainmarket.com',
    ssn: '32869207861448',
    profile_pic: '',
    phone_number: '+351 479 804 3535',
    clinic_address: '40 Anniversary Alley',
    certifications: [
      {
        id: 5,
        name: 'certification 1'
      },
      {
        id: 10,
        name: 'certification 2'
      }
    ],
    session_price: 490.43,
    rating: 1.50
  },
  {
    id: 72,
    name: 'Yasmin Khaled',
    email: 'sarndell3@time.com',
    ssn: '30582594955749',
    profile_pic: '',
    phone_number: '+62 171 282 3960',
    clinic_address: '4419 Cottonwood Avenue',
    certifications: [],
    session_price: 250,
    rating: 2.20
  },
  {
    id: 73,
    name: 'Moustafa Magdy',
    email: 'bradbourn7@slate.com',
    ssn: '21320373873718',
    profile_pic: '',
    phone_number: '+53 558 969 0632',
    clinic_address: '6 Almo Court',
    certifications: [],
    session_price: 323.83,
    rating: 3.70
  },
  {
    id: 75,
    name: 'Mohamed Samy',
    email: 'bciccotto9@ibm.com',
    ssn: '21467984152621',
    profile_pic: '',
    phone_number: '+53 558 969 0632',
    clinic_address: '301 Derek Circle',
    certifications: [],
    session_price: 437.26,
    rating: 1.10
  },
  {
    id: 76,
    name: 'Farag Ahmed',
    email: 'bsygroves0@domainmarket.com',
    ssn: '32869207861448',
    profile_pic: '',
    phone_number: '+351 479 804 3535',
    clinic_address: '40 Anniversary Alley',
    certifications: [
      {
        id: 5,
        name: 'certification 1'
      },
      {
        id: 10,
        name: 'certification 2'
      }
    ],
    session_price: 490.43,
    rating: 1.50
  },
  {
    id: 77,
    name: 'Omnia Salah',
    email: 'rmadison1@jugem.jp',
    ssn: '25133616462649',
    profile_pic: '',
    phone_number: '+48 879 189 5185',
    clinic_address: '2630 Cambridge Park',
    certifications: [
      {
        id: 1,
        name: 'certification 3'
      },
      {
        id: 2,
        name: 'certification 4'
      }
    ],
    session_price: 450.97,
    rating: 2.5
  },
]

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
      doctors: [...tmpDoctors],

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

      savePatient({commit}, patient){
        delete patient.profile_picture_url;
        let response;
        // if the patient has ID -> update method
        if(patient.id){
          response = axiosClient.put(`/patients/${patient.id}`, patient)
            .then((res) => {
              commit("updatePatient", res.data);
              return res;
            });
        }
        else {
          response = axiosClient.post('/patients', patient)
            .then((res) => {
              commit("savePatient", res.data);
              return res;
            });
        }
        return response;
      },

      saveDoctor({commit}, doctor){
        delete doctor.profile_picture_url;
        let response;
        // if the doctor has ID -> update method
        if(doctor.id){
          response = axiosClient.put(`/doctors/${doctor.id}`, doctor)
            .then((res) => {
              commit("updateDoctor", res.data);
              return res;
            });
        }
        else {
          response = axiosClient.post('/doctors', doctor)
            .then((res) => {
              commit("saveDoctor", res.data);
              return res;
            });
        }
        return response;
      },

    },
    mutations: {
      savePatient: (state, patient) => {
        state.patients = [...state.patients, patient.data];
      },
      updatePatient: (state, patient) => {
        state.patients = state.patients.map((s) => {
          if(s.id == patient.data.id){
            return patient.data;
          }
          return s;
        });
      },

      saveDoctor: (state, doctor) => {
        state.doctors = [...state.doctors, doctor.data];
      },
      updateDoctor: (state, doctor) => {
        state.doctors = state.doctors.map((s) => {
          if(s.id == doctor.data.id){
            return doctor.data;
          }
          return s;
        });
      },

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
