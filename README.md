# International date line crossing

Display correct itinerary items when a ship crosses the IDL. The IDL crossing needs
to be configured with one of the following options:

- **NO** - normal item
- **EASTBOUND** - item represents IDL crossing Eastbound, gaining a day. The item however is just a marker, it does
                  not take any time (e.g. Day3: IDL crossing, Day3: watching sea-turtles mate).
- **EASTBOUND_DAY** - item represents IDL crossing Eastbound, gaining a day. The item represents a day of activity
                  and will count as a day as well (e.g. Day3: Crossing IDL and watching sea-turtles mate).
- **WESTBOUND** - item represents IDL crossing Westbound, loosing a day. The item however is just a marker, it does
                  not take any time (e.g. Day3: IDL crossing, Day3: watching sea-turtles mate).
- **WESTBOUND_DAY** - item represents IDL crossing Westbound, loosing a day. The item represents a day of activity
                  and will count as a day as well (e.g. Day3: Crossing IDL and watching sea-turtles mate).

Example crossings are:

1) Ship sails from **Asia to US** across the pacific ocean (going EASTBOUND) - you gain a day as you are loosing hours
all the time

| Index | Day | Date       | IDL |
|-------|-----|------------|-----|
| 0     | 1   | 2016-12-24 |     |
| 1     | 2   | 2016-12-25 |     |
| 2     | 2   | 2016-12-25 | `EASTBOUND`   |
| 3     | 3   | 2016-12-25 |     |
| 4     | 4   | 2016-12-26 |     |

2) Same, but the crossing represents a whole day

| Index | Day | Date       | IDL |
|-------|-----|------------|-----|
| 0     | 1   | 2016-12-24 |     |
| 1     | 2   | 2016-12-25 |     |
| 2     | 3   | 2016-12-26 | `EASTBOUND_DAY`   |
| 3     | 4   | 2016-12-26 |     |
| 4     | 5   | 2016-12-27 |     |

3) Ship sails from **US to Asia** across the pacific ocean (going EASTBOUND) - you loose a day as you are gaining hours
all the time

| Index | Day | Date       | IDL |
|-------|-----|------------|-----|
| 0     | 1   | 2016-12-24 |     |
| 1     | 2   | 2016-12-25 |     |
| 2     | 2   | 2016-12-25 | `WESTBOUND`   |
| 3     | 3   | 2016-12-27 |     |
| 4     | 4   | 2016-12-28 |     |

4) Same, but the crossing represents a whole day

| Index | Day | Date       | IDL |
|-------|-----|------------|-----|
| 0     | 1   | 2016-12-24 |     |
| 1     | 2   | 2016-12-25 |     |
| 2     | 3   | 2016-12-26 | `WESTBOUND_DAY`   |
| 3     | 4   | 2016-12-28 |     |
| 4     | 5   | 2016-12-29 |     |

# Usage

Please see the tests for examples. Basically you define a list of itinerary items and set the IDL options on some of
them. Days and dates will then be adjusted accordingly.