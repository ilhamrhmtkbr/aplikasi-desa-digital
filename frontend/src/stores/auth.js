import {defineStore} from "pinia";
import Cookies from "js-cookie";
import {axiosInstance} from "@/plugins/axios.js";
import router from "@/router/index.js";
import {handleError} from "@/helpers/errorHelper.js";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: Cookies.get('token') || null,
        loading: false,
        error: null,
        success: null
    }),
    getters: {
        isAuthenticated: state => !!state.token
    },
    actions: {
        async login(credentials) {
            this.loading = true
            this.error = null

            try {
                const response = await axiosInstance.post('/login', credentials)
                const token = response.data.token

                // Set token
                this.token = token
                Cookies.set('token', token, { expires: 7 }) // Cookie expires in 7 days

                // Fetch user data setelah login
                await this.checkAuth()

                this.success = 'Login successful'

                // Redirect ke dashboard
                router.push({name: 'dashboard'})

            } catch (error) {
                this.error = handleError(error)
                throw error
            } finally {
                this.loading = false
            }
        },

        async logout() {
            this.loading = true

            try {
                await axiosInstance.get('/logout')
            } catch (error) {
                // Tetap logout meskipun request gagal
                console.error('Logout error:', error)
            } finally {
                // Clear state
                this.token = null
                this.user = null
                this.error = null

                // Remove cookie
                Cookies.remove('token')

                // Redirect ke login
                router.push({ name: 'login' })

                this.success = 'Logout successful'
                this.loading = false
            }
        },

        async checkAuth() {
            this.loading = true

            try {
                const response = await axiosInstance.get('/me')
                this.user = response.data.data
                return this.user
            } catch (error) {
                // Token invalid/expired
                if (error.response && error.response.status === 401) {
                    this.token = null
                    this.user = null
                    Cookies.remove('token')
                }
                throw error
            } finally {
                this.loading = false
            }
        },

        // Helper untuk clear error/success messages
        clearMessages() {
            this.error = null
            this.success = null
        }
    }
})