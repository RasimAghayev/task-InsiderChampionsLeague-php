import axios from "axios";

const API_URL = "http://localhost:8000";

export const fetchLeagues = async () => {
    const response = await axios.get(`${API_URL}/leagues`);
    return response.data;
};

export const fetchLeagueById = async (id) => {
    const response = await axios.get(`${API_URL}/leagues/${id}`);
    return response.data;
};