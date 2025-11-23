import axios from "axios";
import { store } from "./store/auth";

const api = axios.create({
  baseURL: 'http://localhost:8000',
  headers: {
    Accept: 'application/json',
  }
});


api.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default api;
