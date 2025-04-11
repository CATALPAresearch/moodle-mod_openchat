<template>
    <div>
        <label class="file-upload">
            <div class="w50 filename-container">
                <span v-if="isProcessing"><i class="fa fa-spinner fa-spin"></i> Indexierung läuft: </span>
                <span v-if="selectedFile">{{ selectedFile.name }}</span>
            </div>
            <div aria-live="polite" class="sr-only">
                <span v-if="isProcessing">Indexierung läuft</span>
                <span v-else-if="selectedFile">Datei {{ selectedFile.name }} ausgewählt</span>
            </div>
            <div style="display: table-cell;">
                <label 
                    for="pdf-upload" 
                    v-if="!isProcessing" 
                    :class="{'btn btn-primary btn-sm': selectedFile==null, 'btn btn-light btn-sm': selectedFile!=null}">
                    PDF auswählen
                </label>
                <input 
                    id="pdf-upload"    
                    v-if="!isProcessing" 
                    class="btn btn-primary btn-sm" 
                    title="Die hochgeladene Lernressource wird gerade verarbeitet." 
                    type="file" 
                    @change="handleFileUpload" 
                    accept="application/pdf" />
                <button v-if="selectedFile && !isProcessing" class="btn btn-primary btn-sm" @click="sendToWebService">
                    PDF hochladen
                </button>
            </div>
        </label>
        <div v-if="debug && webServiceResponse">
            {{ webServiceResponse }}
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            selectedFile: null,
            isProcessing: false,
            webServiceResponse: null,
            debug: false
        };
    },
    methods: {
        // Handles file selection
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0];
        },

        // Sends the file directly to the external web service
        async sendToWebService() {
            let _this = this;
            if (!this.selectedFile) {
                alert("Please select a file first.");
                return;
            }
            this.isProcessing = true;
            var activity_type = 'pdf';
            var activity_id = Math.floor(Math.random()*30000); // FixMe
            const formData = new FormData();
            const systemContext = this.$store.getters.getSystemContext
            formData.append("file", this.selectedFile);
            formData.append("system", systemContext['systemName']);
            formData.append("course_id", systemContext['courseID']);
            formData.append("activity_type", activity_type);
            formData.append("activity_id", activity_id);

            const url = this.$store.getters.getRAGWebserviceHost + "documents/create_index";
            //console.log('form upload data', formData)
            await axios.post(
                url,
                formData,
                { headers: { "Content-Type": "multipart/form-data" } }
            ).then(response => {
                this.webServiceResponse = {
                    error: false,
                    document_index: response.document_index,
                    file: _this.selectedFile,
                    activity_id: activity_id,
                    activity_type: activity_type,
                };
                console.log("Web Service Response:", this.webServiceResponse);
                // send recaived data to parent component
                this.$emit("document_uploaded", this.webServiceResponse);
                
                // reset file upload for the next document
                this.selectedFile = null;
                this.isProcessing = false;
            
            }).catch(error => {
                console.error("Error sending file to web service:", error);
                console.log("Error uploading file.");
                this.webServiceResponse = { 
                    error: true,
                    msg: 'Bei der Verarbeitung des hochgeladenen Dokuments ist ein Problem aufgetreten',
                    error_detail: error
                }
            });
        },
    },
};
</script>

<style scoped>
input {
    margin-bottom: 10px;
}

button {
    margin-left: 10px;
    padding: 8px 0px 8px 12px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}

button:disabled {
    background-color: #ccc;
}

input[type="file"] {
    display: none;
}
.file-upload {
    display: inline-block;
    cursor: pointer;
}
.fa-spinner {
  margin-right: 5px;
  font-size: 1.2em;
}

.filename-container {
  display: table-cell;
  max-width: 250px;  /* Adjust width as needed */
  white-space: normal;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

</style>