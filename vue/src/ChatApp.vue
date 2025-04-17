<template>
  <div v-cloak>
    <div id="container" class="container-fluid consent mt-3" v-cloak>
      <div class="row justify-content-center" v-cloak>
        <ChatInformedConsent></ChatInformedConsent>
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
import ChatInformedConsent from "./components/ChatInformedConsent.vue";
import Communication from "./classes/communication";
import { library } from '@fortawesome/fontawesome-svg-core'
import { faThumbsUp, faThumbsDown, faCopy, faCog, faCheck, faSpinner, faArrowUp, faCommentDots, faTrash, faClose } from '@fortawesome/free-solid-svg-icons'
//import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome' // why don't we need this?

library.add(faThumbsUp)
library.add(faThumbsDown)
library.add(faCopy)
library.add(faCog)
library.add(faCheck)
library.add(faSpinner)
library.add(faCommentDots)
library.add(faArrowUp)
library.add(faTrash)
library.add(faClose)

export default {
  name: "ChatApp",
  components: {
    ChatInformedConsent: ChatInformedConsent
  },
  data() {
    return {
    };
  },
  mounted: function(){
    var _this = this;
    Communication.webservice("triggerEvent", {
      cmid: _this.$store.getters.getCMID,
      action: "view_openchat",
      value: JSON.stringify({opened: 'OpenChat'}),
    });
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


[v-cloak] .v-cloak--hidden{
  display: none !important;
}
</style>
