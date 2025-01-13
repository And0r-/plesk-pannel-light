<template>
    <div>
      <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-100">
              <th class="border border-gray-300 px-4 py-2">Domain Name</th>
              <th class="border border-gray-300 px-4 py-2">Creation Date</th>
              <th class="border border-gray-300 px-4 py-2">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="domain in domains" :key="domain.id">
              <td class="border border-gray-300 px-4 py-2">{{ domain.name }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ domain.creationDate }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ domain.status }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      domains: {
        type: Array,
        default: () => [],
      },
    },
  };
  </script>
  