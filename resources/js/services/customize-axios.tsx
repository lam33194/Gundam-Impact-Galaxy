import axios from "axios";

const instance = axios.create({
  baseURL: "http://127.0.0.1:8000",
});

// Add a response interceptor

instance.interceptors.request.use(config => {
  config.headers.Authorization = 'Bearer ' + String(localStorage.getItem("token"));
  return config;
});

export default instance;