<template>
    <card class="card relative px-6 py-4 card-panel" :class="isDraft() ? 'bg-draft' : isOnline() ? 'bg-published' : 'bg-for-publication'">
        <div class="flex mb-4">
            <h3 class="mr-3 text-50 font-bold">{{ __('Status') }}</h3>
        </div>
        <p class="flex items-center text-3xl mb-3 text-white">
            {{ isDraft() ? __('Draft') : isOnline() ? __('Published') : __('For publication') }}
        </p>
    </card>
</template>

<script>
export default {
    props: [
        'card',

        // The following props are only available on resource detail cards...
        'resource',
        // 'resourceId',
        // 'resourceName',
    ],

    mounted() {

    },
    data: function () {
        return {
            publicationDate: this.resource.fields.find(el => el.attribute == 'publication_date').value
        }
    },
    methods: {
        isDraft: function() {
            return this.publicationDate == null || this.publicationDate == "";
        },
        isOnline: function () {
            if(this.publicationDate == null) return false;
            return Date.parse(this.publicationDate) < new Date();
        }
    }
}
</script>

<style>
    .bg-for-publication{
        background: #2980b9;
    }

    .bg-draft{
        background: #f66757;
    }

    .bg-published{
        background: #27ae60;
    }
</style>
