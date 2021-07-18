<template>
    <div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <inertia-link v-if="$page.props.user" href="/dashboard" class="text-sm underline">
                Dashboard
            </inertia-link>
        </div>

        <div class="container mx-auto px-4 text-center">
            <div class="grid grid-cols-1 gap-4">
                <div class="left">
                    <div class="px-4">Bonus balance: <span class="text-2xl">{{ balance.bonus }}</span></div>
                    <div class="px-4">Money balance: <span class="text-2xl">{{ balance.money }}</span></div>
                </div>
                <div>
                    <div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded"
                            v-on:click="play()">
                            Get prize
                    </button>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <div class="text-2xl">Money prizes</div>
                        <ul v-for="moneyPrize in moneyPrizes">
                            <li class="py-3">
                                <span class="px-4">
                                    {{ moneyPrize.amount }}
                                </span>
                                <button
                                    v-if="!moneyPrize.withdrawn && !moneyPrize.converted"
                                    v-on:click="withdrawMoney(moneyPrize.id)"
                                    class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded-full"
                                >
                                    Withdraw
                                </button>
                                <button
                                    v-if="!moneyPrize.withdrawn && !moneyPrize.converted"
                                    v-on:click="convertMoney(moneyPrize.id)"
                                    class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded-full"
                                >
                                    Convert
                                </button>
                                <span
                                    v-if="moneyPrize.withdrawn"
                                    class="bg-gray-500 text-white py-1 px-2 rounded-full"
                                >
                                    Withdrawn
                                </span>
                                <span
                                    v-if="moneyPrize.converted"
                                    class="bg-gray-500 text-white py-1 px-2 rounded-full"
                                >
                                    Converted
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <div class="text-2xl">Bonus prizes</div>
                        <ul v-for="bonusPrize in bonusPrizes">
                            <li class="py-3">
                                <span class="px-4">
                                    {{ bonusPrize.amount }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <div class="text-2xl">Subject prizes</div>
                        <ul v-for="subjectPrize in subjectPrizes">
                            <li class="py-3">
                                <span class="px-4">
                                    {{ subjectPrize.title }}
                                </span>
                                <button
                                    v-if="!subjectPrize.refused && !subjectPrize.sent"
                                    v-on:click="refuseSubject(subjectPrize.id)"
                                    class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded-full"
                                >
                                    Refuse
                                </button>
                                <span
                                    v-if="subjectPrize.refused"
                                    class="bg-gray-500 text-white py-1 px-2 rounded-full"
                                >
                                    Refused
                                </span>
                                <span
                                    v-if="subjectPrize.sent"
                                    class="bg-gray-500 text-white py-1 px-2 rounded-full"
                                >
                                    Sent
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<style scoped>

</style>

<script>
import Button from "../Jetstream/Button";

const apiRequest = async (route, payload) => {
        const response = await axios.post(route, payload, {
            headers : {
                'Content-Type': 'application/json',
            },
            withCredentials: true
        });

        return response.data;
    }

    export default {
        components: {
            Button
        },
        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
            balance: {
                money: Number,
                bonus: Number,
            },
            moneyPrizes: {
                id: Number,
                user_id: Number,
                amount: Number,
                withdrawn: Boolean,
                converted: Boolean,
                created_at: String,
                updated_at: String,
            },
            bonusPrizes: {
                id: Number,
                user_id: Number,
                amount: Number,
                created_at: String,
                updated_at: String,
            },
            subjectPrizes: {
                id: Number,
                title: String,
                refused: Boolean,
                sent: Boolean,
                created_at: String,
                updated_at: String,
            },
        },
        methods: {
            async convertMoney(id) {
                const index = this.moneyPrizes.findIndex((item => item.id === id));
                this.moneyPrizes[index].converted = true;
                await apiRequest(`/api/convert-money/${id}`, {});
                this.balance.bonus = await apiRequest('/api/bonus-balance', {});
            },
            async withdrawMoney(id) {
                const index = this.moneyPrizes.findIndex((item => item.id === id));
                this.moneyPrizes[index].withdrawn = true;
                await apiRequest(`/api/withdraw-money/${id}`, {});
                this.balance.money = await apiRequest('/api/money-balance', {});
            },
            async refuseSubject(id) {
                const index = this.subjectPrizes.findIndex((item => item.id === id));
                this.subjectPrizes[index].refused = true;
                await apiRequest(`/api/refuse-subject/${id}`, {});
            },
            async play() {
                try {
                    const response = await apiRequest('/api/play', {});

                    if ('subject' in response) {
                        this.subjectPrizes.unshift(response.subject);
                    }

                    if ('money' in response) {
                        this.moneyPrizes.unshift(response.money);
                    }

                    if ('bonus' in response) {
                        this.bonusPrizes.unshift(response.bonus);
                        this.balance.bonus = await apiRequest('/api/bonus-balance', {});
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        },
    }
</script>
