import axios from "axios";

const web = axios.create({
  baseURL: "http://127.0.0.1:8000",  // root aplikasi Laravel
  withCredentials: true,              // kirim cookie & XSRF-TOKEN
  headers: {
    Accept: "application/json",
  },
});

export default web;

