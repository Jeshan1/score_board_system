<template>
  <div class="bg-gray-100 min-h-screen">
    <Header></Header>

    <div class="container mx-auto py-8">
      <h1 class="text-2xl font-bold mb-4 text-center">Upcoming Matches</h1>
      <MatchesCards :matches="matches" />
    </div>

    <Footer></Footer>
  </div>
</template>

<script>
import Header from '../components/Header.vue';
import Footer from '../components/Footer.vue';
import MatchesCards from '../components/landing/MatchList.vue';
import api from '../axios';

export default {
  name: "LandingPage",
  components: { Header, Footer, MatchesCards },
  data() {
    return {
      matches: [],
    };
  },
  mounted() {
    this.fetchMatches();
  },
  methods: {
    async fetchMatches() {
      try {
        const res = await api.get('/api/public/games');
        this.matches = res.data.data;
      } catch (err) {
        console.error("Error fetching matches:", err);
      }
    }
  }
};
</script>

<style scoped>
</style>
