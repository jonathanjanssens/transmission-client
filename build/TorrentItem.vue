<template>

    <div :class="classes">

        <div class="torrents-list-item-row">
            <div class="torrents-list-item-title">
                <span v-if="meta.status == 0">🛑</span>
                <span v-if="meta.status == 4">💉</span>
                <span v-if="meta.status == 6 && !this.active">🌱</span>
                <span v-if="meta.status == 6 && this.active">🌻</span>
                <span>{{ meta.name }}</span>
            </div>
        </div>

        <div class="torrents-list-item-progress">
            <progress :value="meta.downloadedEver" :max="meta.sizeWhenDone"></progress>
        </div>

        <div class="torrents-list-item-row torrents-list-item-status">
            <span>⏫ {{ meta.rateUpload / 1000 }} kb/s</span>
            <span>⏬ {{ meta.rateDownload / 1000 }} kb/s</span>
            <span>↕️ {{ meta.uploadRatio }}</span>
            <span>
                <a href="#" v-on:click.prevent="start" v-if="meta.status == 0">Start</a>
                <a href="#" v-on:click.prevent="stop" v-else>Stop</a>
            </span>
        </div>

    </div>

</template>

<script>

    export default {

        props: ['meta'],

        data() {
            return {
                working: false
            }
        },

        computed: {

            classes() {
                let classes = ['torrents-list-item'];
                if(this.active) {
                    classes.push('torrents-list-item-is-active');
                }
                if(this.working) {
                    classes.push('torrents-list-item-is-working');
                }
                return classes;
            },

            active() {
                return this.meta.rateUpload > 0 || this.meta.rateDownload > 0;
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

            stop() {
                this.working = true;
                axios.patch('/api/torrents/' + this.meta.id, this.params({action: 'stop'})).then(r => {
                    this.working = false;
                });
            },

            start() {
                this.working = true;
                axios.patch('/api/torrents/' + this.meta.id, {action: 'start'}).then(r => {
                    this.working = false;
                });
            }

        }

    }

</script>

<style lang="scss" type="text/scss">

    .torrents-list-item {
        background: white;
        margin-bottom: 1rem;
        position: relative;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 3px rgba(10,10,10,.1),0 0 0 1px rgba(10,10,10,.1);
        transition: opacity 0.3s ease;

        &-title {
            padding: 1.5rem;
            overflow: hidden;
            word-break: break-all;
        }

        &-status {
            margin-top: auto;
            font-size: 0.75rem;

            span {
                flex-grow: 1;
                align-items: center;
                display: flex;
                flex-basis: 0;
                flex-shrink: 0;
                justify-content: center;
                padding: .75rem;
                border-top: 1px solid #dbdbdb;
                &:not(:last-child) {
                    border-right: 1px solid #dbdbdb;
                }
            }
        }

        &-row {
            display: flex;
            align-items: center;
            width: 100%;
        }

        progress {
            vertical-align: baseline;
            -webkit-appearance: none;
            -moz-appearance: none;
            display: block;
            width: 100%;
            border: 0;
            height: 5px;
            overflow: hidden;
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            transition: all 0.3s ease;

            &::-moz-progress-bar {
                background: #8c8c8c;
            }

            &::-webkit-progress-value {
                background: #8c8c8c;
            }

        }

        &-is-active {

            progress {
                &::-moz-progress-bar {
                    background: #4731b6;
                }

                &::-webkit-progress-value {
                    background: #4731b6;
                }

            }
        }

        &-is-working {
            opacity: 0.3;
        }

    }

</style>
