import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.cmtyvol.index', params)
        return await api.get(url)
    }
}
