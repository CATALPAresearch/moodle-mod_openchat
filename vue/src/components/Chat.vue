<template>
  <div id="container" class="content">
    <div id="chat">
      <div class="w-100">
        <div
          v-for="m in messages"
          key="m"
          :class="m.author == 'bot' ? 'chat-message ml-auto' : 'chat-message'"
          :style="
            m.author == 'bot'
              ? 'background-color:azure;'
              : 'background-color:cornsilk;'
          "
        >
          {{ m.message }}
        </div>
      </div>
      <div class="row w-100 chat-input">
        <input
          type="text"
          class="col-6"
          v-model="chat_message"
          @keyup.enter="requestChat"
          placeholder=""
        />
        <button class="btn btn-primary col-2" @click="requestChat">send</button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from "vue";
import store from "./store";

export default Vue.extend({
  name: "OpenChat",
  data() {
    return {
      chat_message: "",
      messages: [],
    };
  },
  mounted: function () {},
  store: store as any,
  methods: {
    //@ignore-ts
    requestChat: async function () {
      let _this = this;
      let message = this.chat_message;
      this.chat_message = ""; // reset input field
      //@ts-ignore
      this.messages.push({ author: "user", message: message });
      //@ts-ignore
      let message_pos = this.messages.push({ author: "bot", message: "" });
      
      const url = "https://catalpa-llm.fernuni-hagen.de/ollama/api/generate";
      const apiKey =
        "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjczYmUyMGFiLWI4YjYtNDNmNS05YmZjLWIzMDU1OGZkODZiYyJ9.7QCdTgHAPVvTJgkbr7NLxYcO4iUTwlL4ai6rfw_neXE"; // Replace with your actual API key
      const payload = {
        model: "llama3.1",
        prompt: message,
      };

      try {
        // send request
        const response = await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + apiKey,
          },
          body: JSON.stringify(payload),
        });

        if (!response.ok) {
          throw new Error("HTTP error! Status:" + response.status);
        }

        // Handle response
        //@ts-ignore
        const reader = response.body != null ? response.body.getReader() : null;
        const decoder = new TextDecoder("utf-8");
        let done = false;

        while (!done) {
          //@ts-ignore
          const { value, done: readerDone } = await reader.read();
          done = readerDone;

          if (value) {
            const chunk = decoder.decode(value, { stream: true });
            let res = "";
            try {
              res = JSON.parse(chunk).response;
            } catch (e) {
              res = "";
            }
            //@ts-ignore
            _this.messages[message_pos - 1].message =
              _this.messages[message_pos - 1].message + res;
          }
        }
      } catch (error) {
        console.error("Error fetching streaming data:", error);
      }
    },
  },
});
</script>

<style scoped>
.content {
  border: none;
  max-width: 830px;
  margin: 0 auto;
}
#chat {
  display: block;
  width: 500px;
}
#chat input {
  font-size: 1.1em;
  padding: 2px;
  margin-right: 2px;
}
#chat .chat-input {
  margin-top: 30px;
  padding-top: 10px;
  border-top: #222222 1px solid;
}
#chat .chat-message {
  display: block;
  padding: 8px 10px;
  font-size: 1.1em;
  margin-bottom: 5px;
  width: 300px;
  border-radius: 2% 2%;
  border: solid 1px #555;
}
</style>
