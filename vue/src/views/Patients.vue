<template>

  <pageComponent>

    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Patients</h1>
        <router-link :to="{ name: 'PatientCreate'}" class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 -mt-1 inline-block">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add new Patient
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
                  Email
                </th>
                <th scope="col" class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase">
                  SSN
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
              <tr v-for="patient in patients" :key="patient.id">
                <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                  {{ patient.id }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                  {{ patient.name }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                  {{ patient.email }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                  {{ patient.ssn }}
                </td>
                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                  <router-link :to="{ name: 'PatientView', params: { id: patient.id } }" class="text-green-500 hover:text-green-700">
                    Edit
                  </router-link>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                  <!--
                  <router-link :to="{ name: 'PatientDelete', params: { id: patient.id } }" class="text-red-500 hover:text-red-700">
                    Delete
                  </router-link>
                  h-4 w-4 flex item-center justify-center rounded-full border border-transparent text-sm text-red-500 focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                  -->
                  <button v-if="patient.id" type="button" @click="deletePatient(patient)" class="text-red-500 hover:text-red-700">
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

const patients = computed(() => store.state.patients);

function deletePatient(patient) {
  if(confirm(`Are you sure you want to delete this patient?`)){

  }
}

</script>
