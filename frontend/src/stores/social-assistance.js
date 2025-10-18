import { handleError } from '@/helpers/errorHelper';
import { axiosInstance } from '@/plugins/axios';
import router from "@/router/index.js";
import { defineStore } from 'pinia';
import {buildFormData} from "@/helpers/formHelper.js";

export const useSocialAssistanceStore = defineStore('social-assistance', {
  state: () => ({
    data: [],
    meta: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0
    },
    loading: false,
    error: null,
    success: null
  }),
  actions: {
    async fetchAll(params) {
      this.loading = true;
      try {
        const response = await axiosInstance.get('/social-assistance', params);
        this.data = response.data.data ?? null;
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },
    async fetchAllPaginated(params) {
      this.loading = true;
      try {
        const response = await axiosInstance.get('/social-assistance/all/paginated', params);
        this.data = response.data.data.data ?? null;
        this.meta = response.data.data.meta
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },
    async fetchById(id) {
      this.loading = true;
      try {
        const response = await axiosInstance.get(`/social-assistance/${id}`);
        return response.data.data ?? null;
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },
    async create(formData) {
      this.loading = true;
      try {
      const fd = buildFormData(formData);
        const response = await axiosInstance.post('/social-assistance', fd, {
                                    headers: { 'Content-Type': 'multipart/form-data' }
                                });
        this.success = response.data.message;
        router.push({name: 'socialAssistance'})
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },
    async edit(formData, id) {
      this.loading = true;
      try {
        const fd = buildFormData(formData);
        fd.append('_method', 'PATCH');
        const response = await axiosInstance.post(`/social-assistance/${id}`, fd, {
                            headers: { 'Content-Type': 'multipart/form-data' }
                        });
        this.success = response.data.message;
        router.push({name: 'socialAssistanceManage', params: {id: id}})
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },
    async destroy(id) {
      this.loading = true;
      try {
        const response = await axiosInstance.delete(`/social-assistance/${id}`);
        this.success = response.data.message;
      } catch (error) {
        this.error = handleError(error);
      } finally {
        this.loading = false;
      }
    },
  },
})
