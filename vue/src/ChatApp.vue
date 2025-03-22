<template>
  <div>
    <nav class="content mb-3">
      <router-link to="/chat" class="tab" active-class="active">General Chat</router-link>
      <router-link to="/rag-chat" class="tab" active-class="active">RAG Chat</router-link>
      <router-link to="/srl-chat" class="tab" active-class="active">SRL Interview</router-link>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script setup></script>

<style scoped>
.content {
    border: none;
    max-width: 830px;
    margin: 0 auto;
}
nav {
  display:flex;
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
