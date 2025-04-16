<template>
  <div>
    <div id="container" class="container-fluid consent mt-3">
      <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
          <div
            v-if="$store.getters.getInformedConsentAgreement != 'yes' && $store.getters.getInformedConsentAgreement != 'no'"
            class="mb-3 alert alert-primary" role="alert">
            <h5>Diese Anwendung ist Teil der KI-Experimentierumgebung und erfordert Ihre Einwilligung.</h5>
            <button @click="$store.commit('setInformedConsentAgreement', 'no')"
              class="btn btn-primary btn-block">Einwilligungserklärung jetzt anzeigen</button>
          </div>
          <div v-if="$store.getters.getInformedConsentAgreement == 'no'" class="alert alert-info" role="dialog">
            <h5>Einwilligung in die Nutzung von Openchat</h5>
            <h4>Zweck der Untersuchung in OpenChat</h4>
            <p class="lead text-wrap">Die Untersuchung in Openchat dient dazu die technischen und didaktischen Voraussetzungen für die Anwendung
              von Large Language Models (LLMs) für das Lernen und Lehren zu ermitteln und herzustellen.</p>
            <h4>Erfasste und verarbeitete Daten</h4>
            <p class="lead text-wrap">
              Während der Nutzung von Openchat werden Interaktionen wie Klicks, Texteingaben und die für den Dokument-Chat
              bereitgestellten Dokumente (bspw. PDFs, Moodle-Ressourcen) sowie die Antworten der Sprachmodelle in
              pseudonymisierter Form erfasst.
              Bitte laden Sie keine Dokumente hoch, die personenbezogene Daten Dritter enthalten. Machen Sie keine Eingaben,
              die personenbezogene Daten Dritter enthalten.
              Nach der Erfassung und Speicherung dieser Daten ist eine Zuordnung von Pseudonym und Nutzenden oder Moodle
              Nutzer-ID nicht mehr möglich.</p>
            <h4>Verwendung der Daten</h4>
            <p class="lead text-wrap">
              Die erfassten Daten werden verwendet, um den technischen Betrieb und Benutzerfreundlichkeit von Openchat und
              den verbundenen Diensten (bspw. RAG-Webservice, FLEXI LLM-Server) zu überwachen, zu analysieren und zu
              verbessern.
              Selbige Daten werden auch genutzt und wissenschaftliche Fragestellungen zur Nutzung und Qualität von
              Lehrangeboten im Zusammenhang mit Sprachmodellen zu beantworten.
              Die Verarbeitung erfolgt auf Grundlage Ihrer freiwilligen Einwilligung (Art. 6 Abs. 1 lit. a DSGVO).
              Ihre Einwilligung kann jederzeit mit Wirkung für die Zukunft widerrufen werden.

              Die Verarbeitung erfolgt ausschließlich auf Servern der FernUniversität im Einklang mit den geltenden
              Datenschutzbestimmungen.
            </p>
            <h4>Rechte</h4>
            <p class="lead text-wrap">
              Nutzenden Studierenden und Lehrende sind nicht verpflichtet Openchat zu nutzen. Die Einwilligung in die
              Erfassung und Verarbeitung der oben genannten Daten erfolgt freiwillig durch eine Zustimmung (opt-in). Die
              Einwilligung kann jeder Zeit widerrufen werden.
              Lehrende könne Openchat freiwillig im Rahmen eines Moodle-Kurses einsetzen und den Kursteilnehmenden anbieten.
              Durch Löschung der Openchat-Aktivität aus dem Kurs kann die Nutzung durch Studierende jeder Zeit unterbunden
              werden.
              Daten von Nutzenden können weder herausgegeben, noch gelöscht werden, da die Daten pseudonym erfasst werden
              und ein Bezug zu einer Person nicht herstellbar ist.
            </p>
            <h4>Verantwortlichkeiten</h4>
            <p class="lead text-wrap">
              Verantwortlich für die Datenverarbeitung ist die FernUniversität in Hagen, Universitätsstraße 11, 58097 Hagen.
              Bei Fragen wenden Sie sich bitte an den <a href="mailto:datenschutzbeauftragter@fernuni-hagen.de">Behördlichen Datenschutzbeauftragten</a>.
              Technische Fragen beantworten Ihnen <a href="mailto:niels.seidel@fernuni-hagen.de">Dr. Niels Seidel</a> und <a href="mailto:torsten.zesch@fernuni-hagen">Prof. Dr. Torsten
              Zesch</a>.
            </p>
            <hr>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch mb-3">
              <button 
                type="button" 
                @click="$store.commit('setInformedConsentAgreement', 'yes')" 
                class="btn btn-primary mb-2 mb-md-0 mr-md-3 w-100 w-md-auto"
                aria-label="Einwilligung erteilen"
                >
                Ja, ich erteile die Einwilligung zur Erhebung und Verarbeitung meiner Daten in Openchat!
              </button>
              <button 
                type="button" @click="$store.commit('setInformedConsentAgreement', 'none')" 
                class="btn btn-outline-secondary w-100 w-md-auto"
                aria-label="Einwilligung nicht erteilen"
                >
                Nein, ich willige nicht ein.
              </button>
            </div>
          </div>
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
      <div v-if="$store.getters.getInformedConsentAgreement == 'yes'"
        @click="$store.commit('setInformedConsentAgreement', 'no')" class="btn btn-link">Einwilligung widerufen</div>
      <div v-if="$store.getters.getIsAdmin" @click="$store.commit('setInformedConsentAgreement', 'none')"
        class="btn btn-link">[Teacher] Einwilligung zurücksetzen</div>
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

.consent p {
  font-size: 1.1em;
}

.consent h4 {
  font-size: 1.1em;
  margin-bottom: 2px;
}

.consent p {
  margin-bottom: 4px;
  margin-top: 0px
}

.consent a {
  text-decoration: underline;
  font-weight: 100;
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
