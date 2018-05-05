<template>

    <div class="torrents-list-container">
        <div class="torrents-list">
            <torrent-item v-for="torrent in torrents" :key="torrent.id" :meta="torrent" class="torrents-list-item"></torrent-item>
        </div>
    </div>

</template>

<script>

    export default {

        props: {
            torrents: {
                default() {
                    return []
                }
            }
        }

    }

</script>

<style lang="scss" type="text/scss">

    .torrents-list {

        &-container {
            overflow: hidden;
        }

        @media (min-width: 800px) {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -1rem;

            &-item {
                width: calc((100% / 2) - 2rem);
                margin: 1rem;
             }
        }
        @media (min-width: 1000px) {
            &-item {
                width: calc((100% / 3) - 2rem);
            }
        }
    }


</style>
