<template>
    <div class="app">
        <div class="toolbar">
            <div class="toolbar-item">Transmission</div>
            <div class="toolbar-item">
                <label for="new-torrent-file-upload">➕ Upload Torrent</label>
                <input id="new-torrent-file-upload" type="file" ref="newTorrentFileUpload" class="sr-only" v-on:change="uploadFile">
            </div>
            <div class="toolbar-item">
                <label for="search-bar" class="sr-only">Search</label>
                <input type="search" id="search-bar" v-model="search" placeholder="Search Torrents" class="search-bar">
            </div>
            <div class="toolbar-item">
                <label for="torrents-sort" class="sr-only">Sort</label>
                <select name="torrents-sort" id="torrents-sort">
                    <option value="Default">Active First</option>
                </select>
            </div>
            <div class="toolbar-right">
                <div class="toolbar-item">⏫ {{ totalUpSpeed / 1000 }} kb/s</div>
                <div class="toolbar-item">⏬ {{ totalDownSpeed / 1000 }} kb/s</div>
                <div class="toolbar-item">↕️ {{ totalRatio }}</div>
            </div>
        </div>

        <torrent-list :torrents="sortedTorrents"></torrent-list>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                torrents: [],
                search: '',
                sort: 'Default',
                fileUpload: ''
            }
        },

        created() {
            this.getTorrents();
            setInterval(this.getTorrents.bind(this), 2000);
        },

        computed: {
            sortedTorrents() {
                let torrents = this.torrents;
                if(this.search) {
                    torrents = torrents.filter(this.filterSearchTerm.bind(this));
                }
                let sortMethod = 'sort' + this.sort;
                torrents = torrents.sort(this[sortMethod].bind(this));

                return torrents;
            },

            totalUpSpeed() {
                let speed = 0;
                for(let i = 0; i < this.torrents.length; i++) {
                    speed += this.torrents[i].rateUpload;
                }
                return speed;
            },

            totalDownSpeed() {
                let speed = 0;
                for(let i = 0; i < this.torrents.length; i++) {
                    speed += this.torrents[i].rateDownload;
                }
                return speed;
            },

            totalRatio() {
                let up = 0;
                let down = 0;
                for(let i = 0; i < this.torrents.length; i++) {
                    down += this.torrents[i].downloadedEver;
                    up += (this.torrents[i].downloadedEver * this.torrents[i].uploadRatio);
                }
                return (up / down).toFixed(2);
            }

        },

        methods: {

            params(obj) {
                let params = new URLSearchParams();
                for(let k in obj) {
                    if(obj.hasOwnProperty(k))
                        params.append(k, obj[k]);
                }
                return params;
            },

            getTorrents() {
                axios.get('api/torrents').then(r => {
                    this.torrents = r.data.torrents;
                });
            },

            filterSearchTerm(item) {
                return item.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
            },

            sortDefault(item) {
                return item.status !== 6;
            },

            uploadFile() {
                let file = this.$refs.newTorrentFileUpload.files[0];
                let formData = new FormData();
                formData.append('file', file);

                axios.post('/api/torrents', formData, {
                    headers: {'Content-Type': 'multipart/form-data'}
                }).then((r => {
                    console.log(r);
                }));
            }

        }

    }
</script>

<style lang="scss" type="text/scss">

    body {
        padding: 0;
        margin: 0;
    }

    .app {
        background: #efefef;
        padding: 1rem;
        min-height: 100vh;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji",
        "Segoe UI Emoji", "Segoe UI Symbol";
        line-height: 1.5;
    }

    .toolbar {
        margin-bottom: 1rem;
        background: white;
        display: flex;
        align-items: center;
        padding: 0.25rem 1rem;

        &-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
        }

        &-item {
            border-right: 1px solid #dbdbdb;
            padding: 0 1rem;

            &:first-of-type {
                padding-left: 0;
            }
            &:last-of-type {
                padding-right: 0;
                border-right: none;
            }
        }

        label {
            font-size: 0.75rem;
        }

    }

    .search-bar {
        padding: 0.5rem;
        width: 300px;
        max-width: 100%;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        border: 0;
    }

</style>
