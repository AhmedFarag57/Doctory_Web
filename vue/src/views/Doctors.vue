<template>

  <pageComponent>

    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Doctors</h1>
        <router-link :to="{ name: 'DoctorCreate'}" class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 -mt-1 inline-block">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add new Doctor
        </router-link>
      </div>
    </template>

    <div class="flex flex-col">
      <div class="overflow-x-auto">
        <div class="p-1.5 w-full inline-block align-middle">
          <div class="overflow-hidden border rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">


              <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">
                  ID
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">
                  Name
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">
                  Clinic Address
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">
                  Session Price
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">
                  Rating
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-bold text-right text-gray-500 uppercase">
                  Edit
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-bold text-right text-gray-500 uppercase">
                  Delete
                </th>
              </tr>
              </thead>

              <tbody class="divide-y divide-gray-200">
              <tr v-for="doctor in doctors" :key="doctor.id">
                <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                  {{ doctor.id }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                  {{ doctor.name }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                  {{ doctor.clinic_address }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                  {{ doctor.session_price }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                  {{ doctor.rating }}
                </td>
                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                  <router-link :to="{ name: 'DoctorView', params: { id: doctor.id } }" class="text-green-500 hover:text-green-700">
                    Edit
                  </router-link>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                  <!--
                  <router-link :to="{ name: 'DoctorDelete', params: { id: doctor.id } }" class="text-red-500 hover:text-red-700">
                    Delete
                  </router-link>
                  h-4 w-4 flex item-center justify-center rounded-full border border-transparent text-sm text-red-500 focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                  -->
                  <button v-if="doctor.id" type="button" @click="deleteDoctor(doctor)" class="text-red-500 hover:text-red-700">
                    Delete
                  </button>
                </td>
              </tr>
              </tbody>


            </table>
          </div>
        </div>
      </div>
    </div>

  </pageComponent>

</template>

<script setup>
import PageComponent from "../components/PageComponent.vue";
import store from "../store";
import { computed } from "vue";

const doctors = computed(() => store.state.doctors);

function deleteDoctor(doctor) {
  if(confirm(`Are you sure you want to delete this doctor?`)){

  }
}

</script>

