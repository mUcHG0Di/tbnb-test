<template>
    <div>
        <v-simple-table
            v-if="!loading"
        >
            <template v-slot:default>
                <thead>
                    <tr>
                        <th class="text-center">
                            Quantity
                        </th>
                        <th class="text-center">
                            Date
                        </th>
                        <th class="text-center">
                            User involved
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="history in product.history"
                        :key="history.id"
                    >
                        <td>{{ history.quantity }}</td>
                        <td>{{ history.date }}</td>
                        <td>{{ (history.user) ? history.user.name : '' }}</td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>

        <v-progress-circular
            v-if="loading"
            :size="50"
            :width="7"
            color="primary mt-5"
            indeterminate
        ></v-progress-circular>
    </div>
</template>

<script>
export default {
    name: 'history-table',
    props: {
        product: Object,
        loading: Boolean,
    },
}
</script>
