<script>
import filter from "lodash-es/filter";
import map from "lodash-es/map";
import { InteractsWithQueryBuilder } from '@protonemedia/inertiajs-tables-laravel-query-builder';

export default {
    mixins: [ InteractsWithQueryBuilder ],
    methods: {
        getColumnsForQuery(columns) {
            let enabledColumns = filter(columns, (column) => {
                return column.enabled;
            });

            return map(enabledColumns, (column) => {
                return column.key;
            });
        },
    },
}
</script>
