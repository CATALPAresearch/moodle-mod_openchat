<template>
  <div>
    <div id="container" class="content">
      <div 
        v-if="$store.getters.getInformedConsentAgreement != 'yes' && $store.getters.getInformedConsentAgreement != 'no'"
        class="mb-3 alert alert-primary" role="alert"
        >
        <h3>Diese Anwendung ist Teil der KI-Experimentierumgebung und erfordert Ihre Einwilligung.</h3>
        <button @click="$store.commit('setInformedConsentAgreement', 'no')" class="btn btn-primary">Einwilligungserklärung jetzt anzeigen</button>
      </div>
      <div v-if="$store.getters.getInformedConsentAgreement == 'no'" class="alert alert-info" role="alert">
        <h3>Einwilligung</h3>
        <h4>Zweck der Untersuchung</h4>
        <p>...</p>
        <h4>Erfasste und verrbeitete Daten</h4>
        <p>Während der Nutzung von Openchat werden Interaktionen wie Klicks, Texteingaben und die für den Dokument-Chat bereitgestellten Dokumente (bspw. PDFs, Moodle-Ressourcen) sowie die Antworten der Sprachmodelle in pseudonymisierter Form erfasst. Nach der Erfassung und Speicherung dieser Daten ist eine Zuordnung von Pseudonym und Nutzenden oder Moodle Nutzer-ID nicht mehr möglich.</p>
        <h4>Verwendung der Daten</h4>
        <p>Die erfassten Daten werden verwendet, um den technischen Betrieb und Benutzerfreundlichkeit von Openchat und den verbundenen Diensten (bspw. RAG-Webservice, FLEXI LLM-Server) zu überwachen, zu analysieren und zu verbessern. Selbige Daten werden auch genutzt und wissenschaftliche Fragestellungen zur Nutzung und Qualität von Lehrangeboten im Zusammenhang mit Sprachmodellen zu beantworten.</p>
        <h4>Rechte</h4>
        <p>
          Nutzenden Studierenden und Lehrende sind nicht verpflichtet Openchat zu nutzen. Die Einwilligung in die Erfassung und Verarbeitung der oben genannten Daten erfolgt freiwillig durch eine Zustimmung (opt-in). Die Einwilligung kann jeder Zeit widerrufen werden.
          Lehrende könne Openchat freiwillig im Rahmen eines Moodle-Kurses einsetzen und den Kursteilnehmenden anbieten. Durch Löschung der Openchat-Aktivität aus dem Kurs kann die Nutzung durch Studierende jeder Zeit unterbunden werden. 
          Daten von Nutzenden können weder herausgegeben, noch gelöscht werden, da die Daten pseudonym erfasst werden und ein Bezug zu einer Person nicht herstellbar ist.
        </p>
        <hr>
        <div class="d-flex justify-content-between* align-items-center* mb-3">
          <button @click="$store.commit('setInformedConsentAgreement', 'yes')" class="btn btn-primary">Ja, ich erteile die Einwilligung zur Erhebung und Verarbeitung meiner Daten in Openchat!</button>
          <div @click="$store.commit('setInformedConsentAgreement', 'none')" class="btn btn-link">Nein, ich willige nicht ein.</div>
        </div>
      </div>
    </div>
    <div v-if="$store.getters.getInformedConsentAgreement == 'yes'">
      <nav hidden class="content mb-3">
        <router-link to="/llm-chat" class="tab" active-class="active">LLM-Chat</router-link>
        <router-link to="/document-chat" class="tab" active-class="active">Dokumenten-Chat</router-link>
        <router-link to="/srl-chat" class="tab" active-class="active">SRL-Interview</router-link>
      </nav>
      <router-view></router-view>
    </div>
    <div id="container" class="content">
      <div v-if="$store.getters.getInformedConsentAgreement == 'yes'" @click="$store.commit('setInformedConsentAgreement', 'no')" class="btn btn-link">Einwilligung widerufen</div>
      <div v-if="$store.getters.getIsAdmin" @click="$store.commit('setInformedConsentAgreement', 'none')" class="btn btn-link">[Teacher] Einwilligung zurücksetzen</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ChatApp",
  data() {
    return {
    };
  },
  methods: {}
};
</script>

<style scoped>
.content {
  border: none;
  max-width: 830px;
  margin: 0 auto;
}

nav {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.tab {
  padding: 10px;
  text-decoration: none;
  color: black;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.active {
  background-color: #007bff;
  color: white;
}
</style>
