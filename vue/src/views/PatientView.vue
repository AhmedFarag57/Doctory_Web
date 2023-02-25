<template>

  <pageComponent>

    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ model.id ? model.name : "Create a Patient" }}
        </h1>
      </div>
    </template>

    <form @submit.prevent="savePatient">
      <div class="shadow sm:rounded-md sm:overflow-hidden">

        <!-- Form Fields -->
        <div class="px-4 py-5 bg-white space-y-6 sm:pd-6">

          <!-- Profile Picture -->
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Image
            </label>
            <div class="mt-1 flex items-center">
              <img v-if="model.profile_picture_url" :src="model.profile_picture_url" :alt="model.name" class="w-64 h-48 object-cover"/>
              <span v-else class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                <svg class="h-[75%] w-[75%] text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </span>
              <button type="button" class="relative overflow-hidden ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <input type="file" @change="onImageChoose" class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer"/>
                Change
              </button>
            </div>
          </div>
          <!--/ Profile Picture -->

          <hr class="my-4"/>

          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
              Name
            </label>
            <input type="text" name="name" id="name" v-model="model.name" autocomplete="patient_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
          </div>
          <!--/ Name -->

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email
            </label>
            <input type="email" name="email" id="email" v-model="model.email" autocomplete="patient_email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
          </div>
          <!--/ Email -->

          <!--  Password -->
          <div v-if="!model.id">
            <label for="password" class="block text-sm font-medium text-gray-700">
              Password
            </label>
            <input type="password" name="password" id="password" v-model="model.password" autocomplete="doctor_password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
          </div>
          <!--/  Password -->

          <!-- SSN -->
          <div>
            <label for="ssn" class="block text-sm font-medium text-gray-700">
              SSN
            </label>
            <input type="text" name="ssn" id="ssn" v-model="model.ssn" autocomplete="patient_ssn" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
          </div>
          <!--/ SSN -->

          <!-- Phone Number -->
          <div>
            <label for="phone_number" class="block text-sm font-medium text-gray-700">
              Phone Number
            </label>
            <input type="text" name="phone_number" id="phone_number" v-model="model.phone_number" autocomplete="patient_phone_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
          </div>
          <!--/ Phone Number -->

          <!-- Date Of Birth -->
          <div>
            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">
              Date Of Birth
            </label>
            <input type="date" name="date_of_birth" id="date_of_birth" v-model="model.date_of_birth" autocomplete="doctor_date_of_birth" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
          </div>
          <!--/ Date Of Birth -->

          <!-- Gender -->
          <div>
            <label for="gender" class="block text-sm font-medium text-gray-700">
              Gender
            </label>
            <div class="flex items-start">
              <div class="flex items-center h-5 pr-4">
                <input type="radio" name="gender" id="gender" value="male" v-model="model.gender" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                <label for="gender" class="font-medium text-gray-700 pl-2">Male</label>
              </div>
              <div class="flex items-center h-5 pr-4">
                <input type="radio" name="gender" id="gender" value="female" v-model="model.gender" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                <label for="gender" class="font-medium text-gray-700 pl-2">Female</label>
              </div>
            </div>
          </div>
          <!--/ Gender -->

          <!-- is blocked -->
          <div>

          </div>
          <!--/ is blocked -->

        </div>
        <!--/ Form Fields -->


        <!-- Save Button -->
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
          <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hove:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Save
          </button>
        </div>
        <!--/ Save Button -->

      </div>
    </form>

  </pageComponent>

</template>

<script setup>
import { ref } from "vue";
import store from "../store";
import { useRoute, useRouter } from "vue-router";

import PageComponent from "../components/PageComponent.vue";

const route = useRoute();
const router = useRouter();

// Create empty patient
let model = ref({
  name: '',
  email: '',
  password: '',
  ssn: '',
  phone_number: '',
  profile_picture: '',
  gender: '',
  date_of_birth: '',
  isDoctor: false,
});

if(route.params.id) {
  model.value = store.state.patients.find(
    (s) => s.id === parseInt(route.params.id)
  );
}

/**
 * Create or Update Patient
 */
function savePatient() {
  store.dispatch("savePatient", model.value).then(({data}) => {
    router.push({
      name: "PatientView",
      params: {id: data.data.id},
    });
  });
}

function onImageChoose(ev){
  const file = ev.target.files[0];
  const reader = new FileReader();

  reader.onlaod = () => {
    // to save it in database
    model.value.profile_picture = reader.result;
    // to display it here
    model.value.profile_picture_url = reader.result;
  };

  reader.readAsDataURL(file);
}


</script>

