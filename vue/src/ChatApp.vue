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
        ...
        <hr>
        <div class="d-flex justify-content-between* align-items-center* mb-3">
          <button @click="$store.commit('setInformedConsentAgreement', 'yes')" class="btn btn-primary">Ja, ich erteile die Einwilligung!</button>
          <div @click="$store.commit('setInformedConsentAgreement', 'none')" class="btn btn-link">Nein, ich willige nicht ein.</div>
        </div>
      </div>
    </div>
    <div v-if="$store.getters.getInformedConsentAgreement == 'yes'">
      <nav class="content mb-3">
        <router-link to="/llm-chat" class="tab" active-class="active">LLM-Chat</router-link>
        <router-link to="/rag-chat" class="tab" active-class="active">Dokumenten-Chat</router-link>
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
    return {};
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
