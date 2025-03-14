import Vue from 'vue';
import VueRouter from 'vue-router';
import Chat from '../components/Chat.vue';
import RAGChat from '../components/RAGChat.vue';
import SRLChat from '../components/SRLChat.vue';

Vue.use(VueRouter); // ✅ Important: Use VueRouter before creating the instance

const routes = [
  { path: '/', redirect: '/srl-chat' }, // Default route
  { path: '/srl-chat', component: SRLChat },
  { path: '/rag-chat', component: RAGChat },
  { path: '/chat', component: Chat }
];

// FixMe: add moodle path to routes
const router = new VueRouter({ 
//  mode: 'history', 
// base: '/moodle/'
  routes
});

export default router;
