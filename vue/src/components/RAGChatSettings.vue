<template>
    <div class="settings mb-3">
        <h3>Einstellungen</h3>
        <span v-if="documents.length > 0" class="bold">Ausgewählte Dokumente</span>
        <table v-if="documents.length > 0" class="document-table">
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
                        <i class="fa fa-trash delete-icon" @click="removeDocument(doc.id)"></i>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-3">
            <RAGupload @document_uploaded="addDocument"></RAGupload>
            <span style="background-color: red;">{{ error_msg }}</span>
        </div>
        <div hidden class="mt-3">
            TODO: Ressource aus dem Kurs als Dokument hinzufügen;
            [todo: page, longpage, wiki, forum, assign]
        </div>
    </div>
</template>

<script>
import RAGupload from "./RAGupload.vue";
export default {
    name: "RAGChatSettings",
    components: {
        RAGupload: RAGupload
    },
    props: {
        documents: Object
    },
    data() {
        return {
            
        };
    },
    methods: {
        addDocument: function (response) {
            console.log('handle adddocument', response)
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
                selected: 'selected',
            });
        },
        removeDocument: function (activity_id) {
            this.documents = this.documents.filter(doc => doc.id !== activity_id);
        },
    }
}
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