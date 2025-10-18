import {handleError} from '@/helpers/errorHelper';
import {axiosInstance} from '@/plugins/axios';
import router from "@/router/index.js";
import {defineStore} from 'pinia';

export const useProfileVillageStore = defineStore('profile', {
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
        async fetchAll() {
            this.loading = true;
            try {
                const response = await axiosInstance.get('/profile');
                this.data = response.data.data ?? null;
                console.log(response)
            } catch (error) {
                this.error = handleError(error);
            } finally {
                this.loading = false;
            }
        },
        async create(formData) {
            this.loading = true;
            try {
                const response = await axiosInstance.post('/profile', formData, {
                    headers: {'Content-Type': 'multipart/form-data'}
                });
                this.success = response.data.message;
                router.push({name: 'profileVillage'})
            } catch (error) {
                this.error = handleError(error);
            } finally {
                this.loading = false;
            }
        },
        async edit(formData, id) {
            this.loading = true;
            try {
                const response = await axiosInstance.post(`/profile/${id}`, fd, {
                    headers: {'Content-Type': 'multipart/form-data'}
                });
                this.success = response.data.message;
                router.push({name: 'profileVillageManage', params: {id: id}})
            } catch (error) {
                this.error = handleError(error);
            } finally {
                this.loading = false;
            }
        },
    },
})
