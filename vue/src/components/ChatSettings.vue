<template>
    <div class="settings mb-3">
       <button
            type="button"
            class="btn btn-link settings-icon"
            @click="$store.commit('toggleShowSettings', 1)"
            aria-label="Einstellungen schließen"
            style="float:right; cursor: pointer; font-size:1em; color:#555;"
            >
            <font-awesome-icon class="ml-3 mt-1 settings-icon" icon="close" aria-hidden="true"/>
        </button>
        <h3 class="mb-3">Einstellungen</h3>

        <!-- Chat modus -->
        <div class="form-group">
            <h4>Chat-Modus</h4>
            <fieldset>
                <legend class="sr-only">Chat-Modus wählen</legend>
                <label>
                    <input type="radio" value="llm-chat" v-model="chatmodus" @change="updateChatModus" />
                    LLM-Chat (Standard)
                </label>
                <br>
                <label>
                    <input type="radio" value="document-chat" v-model="chatmodus" @change="updateChatModus" />
                    Dokumenten-Chat
                </label>
                <br>
                <label>
                    <input type="radio" value="agent-chat" v-model="chatmodus" @change="updateChatModus" />
                    SRL-Chat als Interview-Agent
                </label>
            </fieldset>
        </div>
        <hr>
        <!-- Settings for the document chat (RAG) -->
        <div v-if="chatmodus == 'document-chat'">
            <h4 id="doc-table-caption">Dokumente für Dokumenten-Chat</h4>
            <span v-if="documents.length > 0" class="bold">Ausgewählte Dokumente</span>
            <table v-if="documents.length > 0" class="document-table" aria-labelledby="doc-table-caption">
                <thead>
                    <tr>
                        <th>Auswahl</th>
                        <th>Dokument</th>
                        <th>Aktivitätstyp</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="doc in documents" :key="doc.id">
                        <td>
                            <input type="checkbox" v-model="doc.selected" />
                        </td>
                        <td>{{ doc.file.name }}</td>
                        <td>{{ doc.activity_type }}</td>
                        <td>
                            <button
                                type="button"
                                class="delete-icon"
                                @click="removeDocument(doc.id)"
                                :aria-label="'Dokument'+ doc.file.name + 'löschen'"
                                >
                                <font-awesome-icon icon="trash" aria-hidden="true"/>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3">
                <RAGupload @document_uploaded="addDocument"></RAGupload>
                <span style="background-color: red" aria-live="assertive">{{ error_msg }}</span>
            </div>
            <div hidden class="mt-3">
                TODO: Ressource aus dem Kurs als Dokument hinzufügen; [todo: page,
                longpage, wiki, forum, assign]
            </div>
        </div>
        <hr>
        <!-- Standard settings for all chat modi -->
        <div class="form-group">
            <h4>Verwendetes Sprachmodel</h4>
            <label for="llmSelect" class="form-label">Wählen Sie ein Sprachmodell aus:</label>
            <select id="llmSelect" class="form-control w50" v-model="model" @change="updateModel">
                <option disabled value="">-- Bitte wählen --</option>
                <option v-for="(m, index) in $store.getters.getLLMModelList" :key="m" :value="m">
                    {{ m }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
import RAGupload from "./RAGupload.vue";
import { mapGetters } from 'vuex'

export default {
    name: "RAGChatSettings",
    components: {
        RAGupload: RAGupload,
    },
    props: {
        documents: Object,
    },
    data() {
        return {};
    },
    created: function () {
        this.model = this.$store.getters.getModel;
        this.chatmodus = this.$store.getters.getChatModus;
        console.log('this.chatmodus', this.chatmodus)
    },

    methods: {
        addDocument: function (response) {
            console.log("handle adddocument", response);
            if (response.error) {
                this.error_msg = response.msg;
                return;
            }
            this.document_index = response.document_index;
            this.documents.push({
                file: response.file,
                activity_type: response.activity_type,
                activity_id: response.activity_id,
                document_index: response.document_index, // needed? FixMe
                selected: "selected",
            });
        },
        removeDocument: function (activity_id) {
            this.documents = this.documents.filter((doc) => doc.id !== activity_id);
        },
        updateChatModus: function () {
            this.$store.commit("setChatModus", this.chatmodus);
            this.$store.dispatch("updatePluginSettings");
            this.$router.push(this.chatmodus);
        },
        updateModel: function () {
            this.$store.commit("setModel", this.model);
            this.$store.dispatch("updatePluginSettings");
        },
    },

    computed: {
        chatmodus: {
            get() {
                return this.$store.state.pluginSettings.chatmodus;
            },
            set(value) {
                this.$store.commit("setChatModus", value);
            },
        },
        model: {
            get() {
                return this.$store.state.pluginSettings.model;
            },
            set(value) {
                this.$store.commit("setModel", value);
            },
        },
    },
};
</script>
<style scoped>
.settings {
    display: block;
    width: 500px;
    background-color: #eee;
    padding: 10px 4px 4px 4px;
    border-radius: 3px;
}

.settings h3 {
    font-size: 1.3em;
}

.settings h4 {
    font-size: 1.1em;
}

.document-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.document-table th,
.document-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.document-table th {
    background-color: #f4f4f4;
}

.delete-icon {
    color: #555;
    cursor: pointer;
}

.delete-icon:hover {
    color: red;
}
</style>
