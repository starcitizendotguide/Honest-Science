<template>
    <b-table
        :data="studios"
        default-sort="since"
        :loading="settings.isLoading"
        class="dark-table highlighted-element studio-table"
        detailed
    >

        <template slot-scope="props">
            <b-table-column field="location" label="Location">
                {{ props.row.location }}
            </b-table-column>

            <b-table-column field="employees" label="Employees" centered>
                <span v-if="props.row.employees[props.row.employees.length - 1].amount > 1">
                    <b-tooltip
                        :label="props.row.employee_tooltip"
                        type="is-dark"
                        dashed
                    >
                        {{ props.row.employees[props.row.employees.length - 1].amount }} ({{ props.row.employees[props.row.employees.length - 1].year }})
                    </b-tooltip>
                </span>
                <span v-else>-</span>
            </b-table-column>

            <b-table-column field="established" label="Established" centered>
                <span>{{ props.row.employees[0].year }}</span>
            </b-table-column>

        </template>

        <template slot="detail" slot-scope="props">
            <article class="media">
                <div class="media-content">
                    <div class="content">
                        <p>{{ props.row.description }}</p>
                        <a href="#" v-if="props.row.image != null" @click="props.row.image.show = true">Show Team</a>
                    </div>
                </div>
                <b-modal  v-if="props.row.image != null" :active.sync="props.row.image.show">
                    <div class="card highlighted-element is-active">
                        <div class="card-image">
                            <figure class="image">
                                <img :src="props.row.image.url_full" :alt="props.row.location">
                            </figure>
                        </div>
                    </div>
                </b-modal>
            </article>
        </template>

    </b-table>
</template>

<script>
export default {
    data: function() {
        return {
            studios: [],
            settings: {
                isLoading: true,
            }
        };
    },
    mounted: function() {

        var studios = [
            {
                location: "Austin, U.S.A.",
                description: "The team at Cloud Imperium Games Texas’ Austin studio (Bee Cave, Texas) is responsible for making the Star Citizen persistent universe a reality! From character artists to engineers, CIG ATX is a creative hotspot tasked with making an entirely new kind of game. The crack DevOps team is also located in Austin, keeping servers running and building out the infrastructure that will be needed to make Star Citizen sing.",
                image: {
                    show: false,
                    url_full: 'assets/images/teams/Austin.jpg',
                },
                employees: [
                    {
                        year: 2013,
                        amount: 34
                    },
                    {
                        year: 2014,
                        amount: 55,
                    },
                    {
                        year: 2015,
                        amount: 57,
                    },
                    {
                        year: 2016,
                        amount: 54
                    },
                    {
                        year: 2018,
                        amount: 71
                    }
                ],
            },

            {
                location: "Los Angeles, U.S.A.",
                description: "Located in sunny Los Angeles, California, CIG LA is Cloud Imperium’s flagship studio. Home to Chris Roberts and much of the company’s leadership, CIG LA represents a cross-section of the company and includes the ship development pipeline, technical designers, high level engineering, community, marketing and more.",
                image: {
                    show: false,
                    url_full: 'assets/images/teams/SantaMonica.jpg',
                },
                employees: [
                    {
                        year: 2013,
                        amount: 14
                    },
                    {
                        year: 2014,
                        amount: 38,
                    },
                    {
                        year: 2015,
                        amount: 41,
                    },
                    {
                        year: 2016,
                        amount: 64
                    },
                    {
                        year: 2018,
                        amount: 75
                    }
                ],
            },

            {
                location: "Manchester, England",
                description: "The heart of Manchester, England is also the heart of Star Citizen’s Squadron 42 single player game. This fully-equipped development team is responsible for creating a unique cinematic experience in the Star Citizen world. A AAA game in itself, Squadron 42 is using groundbreaking cinematic techniques and best-in-the-industry game design to craft a narrative you won’t want to put down.",
                image: {
                    show: false,
                    url_full: 'assets/images/teams/Manchester.jpg',
                },
                employees: [
                    {
                        year: 2014,
                        amount: 68,
                    },
                    {
                        year: 2015,
                        amount: 132,
                    },
                    {
                        year: 2016,
                        amount: 191
                    },
                    {
                        year: 2018,
                        amount: 234
                    }
                ],
            },

            {
                location: "Frankfurt, Germany",
                description: "Frankfurt, Germany is home to Cloud Imperium’s latest internal development studio. Staffed with top CryEngine development experts, Foundry 42 Germany is responsible for developing the pioneering backend technologies that will allow Star Citizen’s persistent universe to shine!",
                image: {
                    show: false,
                    url_full: 'assets/images/teams/Frankfurt.jpg',
                },
                employees: [
                    {
                        year: 2015,
                        amount: 28,
                    },
                    {
                        year: 2016,
                        amount: 54
                    },
                    {
                        year: 2017,
                        amount: 75
                    },
                    {
                        year: 2018,
                        amount: 80
                    }
                ],
            },

            {
                location: "Derby, England",
                description: "The New Guys",
                image: null,
                employees: [
                    {
                        year: 2016,
                        amount: 0
                    },
                    {
                        year: 2018,
                        amount: 15
                    }
                ]
            },
        ];

        studios.forEach(function(item) {
            var tmp = [];

            item.employees.forEach(function(entry) {
                tmp.push(entry.amount + ' (' + entry.year + ')');
            });

            item.employee_tooltip = tmp.join(', ');
        });

        this.studios = studios;
        this.settings.isLoading = false;

    }
}
</script>
